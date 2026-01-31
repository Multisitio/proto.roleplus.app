<?php
class UsuariosController extends AppController
{
    public function identificarse()
    {
        if (Input::post()) {
            $user = (new Usuarios)->identificarse(Input::post());
            if ($user) {
                return Redirect::to('/');
            }
        }
    }

    public function identificarse_google()
    {
        if ((new Usuarios)->identificarseConGoogle()) {
            Session::setArray('toast', '¡Bienvenido a Roleplus!');
            Redirect::to('/');
            return;
        }
        Session::setArray('toast', 'No hemos encontrado ese usuario.');
        Redirect::to('/usuarios/identificarse');
    }

    public function salir()
    {
        Session::delete('idu');
        Session::setArray('toast', '¡Hasta pronto!');
        Redirect::to('/');
    }
}
