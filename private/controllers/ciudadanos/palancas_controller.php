<?php
/**
 */
class PalancasController extends CiudadanosController
{
    #
    public function corazon()
    {
        (new Api)->put_('configuracion', [
            'equal'=> [
                'usuarios_idu' => Session::get('idu'),
                'clave' => 'corazon',
                'valor' => _check::val([5, 10, 20, 50], 5, Input::post('corazon')),
            ]
        ], [
            'equal'=> [
                'usuarios_idu' => Session::get('idu'),
                'clave' => 'corazon',
            ]
        ]);
    }
}
