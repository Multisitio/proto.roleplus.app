<?php
/**
 * https://roleplus.app/api/publicaciones/
 * https://roleplus.app/api/publicaciones/$idu
 * https://roleplus.app/api/publicaciones/$idu/comentarios
 * https://roleplus.app/api/publicaciones/$idu/comentarios/$idu
 */
class ApiController extends AppController
{
	#
    public function __call($name, $params) {
        $action = mb_strtolower($_SERVER['REQUEST_METHOD']) . '_';
        $this->$action(...$params);
        View::select(null, 'json');
    }

	#
    public function get_($table, $idu='', $child_table='', $child_idu='') {
        return $this->data = (new Api)->get_($table, $idu, $child_table, $child_idu);
    }

	#
    public function post_($table, $key, $val) {
        return $this->data = (new Api)->post_($table, $key, $val);
    }

	#
    public function put_($table, $key, $val) {
        return $this->data = (new Api)->put_($table, $key, $val);
    }

	#
    public function delete_($table, $aid = null) {
        return $this->data = (new Api)->delete_($table, $aid);
    }
}
