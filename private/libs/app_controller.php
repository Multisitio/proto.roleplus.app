<?php
require_once CORE_PATH . 'kumbia/controller.php';
/**
 */
abstract class AppController extends Controller
{
    #
    final protected function initialize()
    {
        $this->ver = PRODUCTION ? '26012919' : time();

        if (Input::isAjax()) {
            View::template(null);
        }
    }

    #
    final protected function finalize()
    {
    }
}
