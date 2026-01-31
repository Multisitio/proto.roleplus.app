<?php
require_once CORE_PATH . 'kumbia/controller.php';
/**
 */
abstract class CiudadanosController extends Controller
{
    #
    final protected function initialize()
    {
        $this->ver = '24';

        // if ( ! Session::get('idu')) {
        //    Session::set('idu', '3fc4b9f1');
        // }

        if (Input::isAjax()) {
            View::template(null);
        }
    }

    #
    final protected function finalize()
    {
    }
}
