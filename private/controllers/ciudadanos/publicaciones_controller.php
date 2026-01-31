<?php
/**
 */
class PublicacionesController extends CiudadanosController
{
    #
    public function corazon($idu)
    {
        $experiencia = (new Api)->one_('experiencia', [
            'equal'=> [
                'elemento' => 'publicaciones',
                'de' => Session::get('idu'),
                'para' => $idu,
            ],
        ]);

        if ($experiencia) {
            (new Api)->delete_('experiencia', [
                'equal'=> [
                    'elemento' => 'publicaciones',
                    'de' => Session::get('idu'),
                    'para' => $idu,
                ],
            ]);
        }
        else {
            $corazon = (new Api)->one_('configuracion', [
                'equal'=> [
                    'usuarios_idu' => Session::get('idu'),
                    'clave' => 'corazon',
                ],
            ]) ?: (object)['valor'=>5];

            (new Api)->put_('experiencia', [
                'equal'=> [
                    'elemento' => 'publicaciones',
                    'idu' => _str::uid(),
                    'de' => Session::get('idu'),
                    'para' => $idu,
                    'cuanto' => $corazon->valor,
                ],
            ], [
                'equal'=> [
                    'elemento' => 'publicaciones',
                    'de' => Session::get('idu'),
                    'para' => $idu,
                ],
            ]);
        }

        $this->corazones = (new Api)->all_('experiencia', [
            'equal'=> [
                'elemento' => 'publicaciones',
                'de' => Session::get('idu'),
                'para' => $idu,
            ],
        ], 'para');

        $this->pub = (new Api)->one_('publicaciones', [
            'equal'=> [
                'idu' => $idu,
            ],
        ]);

        View::template(null);
    }

    #
    public function notificar($idu)
    {
        $this->notificar[$idu] = (new Api)->toggle_('acciones', [
            'equal'=> [
                'usuarios_idu' => Session::get('idu'),
                'elemento' => 'publicaciones',
                'idu' => $idu,
                'accion' => 'notificar',
            ],
        ]);

        $this->pub = (object)['idu'=>$idu];

        View::template(null);
    }
}
