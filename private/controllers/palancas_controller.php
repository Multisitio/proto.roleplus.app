<?php
/**
 */
class PalancasController extends AppController
{
    #
    public function index()
    {
        $this->corazon = (new Api)->one_('configuracion', [
            'equal'=> [
                'usuarios_idu' => Session::get('idu'),
                'clave' => 'corazon',
            ],
        ]) ?: (object)['valor'=>5];
    }
}
