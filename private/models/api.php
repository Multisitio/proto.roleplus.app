<?php
/**
 */
class Api extends LiteRecord
{
	# Para obtener recursos
	public function get_($table, $idu='', $child_table='', $child_idu='', $limit=25, $child_limit=25, $slug='', $test=0) {
		if ($idu || $slug) {
			$row = $idu
				? parent::table($table)->row($idu)
				: parent::table($table)->where('slug=?')->vals([$slug])->row();
			if ($child_table) {
				if ($child_idu) {
					$row->$child_table = parent::table($child_table)->row($child_idu);
					return $row;
				}
				$row->$child_table = parent::table($child_table)->where($table."_idu=?")->vals([$idu])->order('id DESC')->limit($child_limit)->rows();
				return $row;
			}
			return $row;
		}
		return parent::table($table)->order('id DESC')->limit($limit)->rows();
	}
	
	public function all_($table, $where, $by='idu') {
		return parent::table($table)->where($where)->rows($by);
	}
	
	public function one_($table, $where) {
		return parent::table($table)->where($where)->row();
	}

	# Para actualizar parte de un recurso
	public function patch_($table, $key, $val) {
	}

	# Para crear un recurso
	public function post_($table, $key, $val) {
	}

	# Para actualizar un recurso o crearlo
	public function put_($table, $set, $where, $test=0) {
		$row = parent::table($table)->where($where)->row();
		#if ($test) _var::die([$table, $set, $where, $test, $row]);
		(empty($row->id))
			? parent::table($table)->set_($set)->add()
			: parent::table($table)
				->set_($set)->where($where)->upd();
	}

	# Para borrar un recurso
	public function delete_($table, $where) {
		parent::table($table)->where($where)->del();
	}

	# Para crear un recurso o borrarlo si existe
	public function toggle_($table, $set) {
		$row = parent::table($table)->where($set)->row();

		(empty($row->id))
			? parent::table($table)->set_($set)->add()
			: parent::table($table)->where($set)->del();

		return parent::table($table)->where($set)->row();
	}
}
