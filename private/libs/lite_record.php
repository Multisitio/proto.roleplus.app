<?php
use Kumbia\ActiveRecord\LiteRecord as ORM;
use Kumbia\ActiveRecord\QueryGenerator;
/**
 */
class LiteRecord extends ORM
{


	public function tables()
    {
		$sql = 'SHOW TABLES';
		$rows = self::all($sql);
		foreach ($rows as $row) {
			$tables[] = array_values((array)$row)[0];
		}
		return $tables;
	}

	# 2
	public function select()
    {
		$fields = $this->fields ?? '*';

		$this->sql = "SELECT $fields FROM " . $this->table();

		$this->sql .= empty($this->where) ? '' : $this->where;

		$this->sql .= empty($this->order) ? '' : $this->order;

		$this->sql .= empty($this->limit) ? '' : $this->limit;
	}

	# 2.1
	public function set_($fields, $test=0)
    {
		if (is_array($fields)) {
			if ( ! empty($fields['equal'])) {
				foreach ($fields['equal'] as $field=>$value) {
					$keys[] = "$field=?";
					$this->vals[] = $value;
				}
			}
			if ( ! empty($fields['like'])) {
				foreach ($fields['like'] as $field=>$value) {
					$keys[] = "$field LIKE ?";
					$this->vals[] = $value;
				}
			}
			$this->set_ = implode(', ', $keys);
			if ($test) _var::die($this);
			return $this;
		}

		$this->set_ = $fields;
		if ($test) _var::die($this);
		return $this;
	}

	# 2.2
	public function table($table=null)
    {
        if ($table) {
            unset($this->fields, $this->table_, $this->set_, $this->where, $this->order, $this->limit, $this->vals, $this->sql);
            $this->table_ = $table;
            return $this;
        }
        return $this->table_ ?? $this->getSource();
	}

	# 2.3
	public function where($where)
    {
		if (is_array($where)) {
			if ( ! empty($where['equal'])) {
				foreach ($where['equal'] as $field=>$value) {
					$keys[] = "$field=?";
					$this->vals[] = $value;
				}
			}
			if ( ! empty($where['like'])) {
				foreach ($where['like'] as $field=>$value) {
					$keys[] = "$field LIKE ?";
					$this->vals[] = $value;
				}
			}
			if ( ! empty($where['in'])) {
				foreach ($where['in'] as $field=>$value) {
					foreach ($value['rows'] as $row) {
						$in[] = '?';
						$this->vals[] = $row->{$value['by']};
					}
					$keys[] = "$field IN (".implode(', ', $in).')';
				}
			}
			$this->where = ' WHERE ' . implode(' AND ', $keys);
			return $this;
		}
		$this->where = $where ? " WHERE $where" : '';
		return $this;
	}

	# 2.4
	public function vals($vals=[])
    {
		$this->vals = $vals;
		return $this;
	}

	# 2.5
	public function order($order)
    {
		$this->order = $order ? " ORDER BY $order" : '';;
		return $this;
	}

	# 3
	public function limit($limit)
    {
		$this->limit = $limit ? " LIMIT $limit" : '';;
		return $this;
	}

	# 4
	public function row($x=0, $test=0)
    {
		if ($x && $x<>'cols') {
			$this->where('id=? OR idu=?');
            $this->vals([$x, $x]);
		}
		$this->select();
		if ($test) _var::die($this);
		$row = empty($this->vals)
			? self::first($this->sql)
            : self::first($this->sql, $this->vals);

		if ($this->table_ <> 'usuarios' && isset($row->usuarios_idu)) {
			$row->usuario = self::table('usuarios')->row($row->usuarios_idu);
		}

		return empty($row) ? (($x=='cols')?self::cols():'') : $row;
	}

    # 4.1
	public function cols()
	{
		$rows = self::all("DESCRIBE $this->table_");
		$a = [];
		foreach ($rows as $row) {
			$a[$row->Field] = '';
		}
		return (object)$a;
	}

	# 5
	public function rows($by='', $test=0)
    {
		$this->select();
		if ($test) _var::die($this);
		$rows = empty($this->vals)
			? self::all($this->sql)
			: self::all($this->sql, $this->vals);
		if ($by) {
			$rows = self::arrayBy($rows, $by);
		}
		return ($this->table_ <> 'usuarios')
			? self::attachUsuario($rows)
			: $rows;
	}

    # 5.1
    public function attachUsuario($rows)
    {
        if (empty($rows)) {
            return $rows;
        }

        $keys = [];
        $vals = [];
        foreach ($rows as $row) {
            if (isset($row->usuarios_idu)) {
                $keys[] = '?';
                $vals[] = $row->usuarios_idu;
            }
        }

        if ( ! empty($vals)) {
            $usuarios = $this->table('usuarios')
                ->where('idu IN (' . implode(',', $keys) . ')')
                ->vals($vals)
                ->rows();

			$usuarios = self::arrayBy($usuarios);

            foreach ($rows as &$row) {
                if (isset($row->usuarios_idu) && isset($usuarios[$row->usuarios_idu])) {
                    $row->usuario = $usuarios[$row->usuarios_idu];
                }
            }
        }

        return $rows;
    }

    # 5.2
	public static function arrayBy($arr_old, $field='idu')
	{
        $arr_new = [];
        foreach ($arr_old as $obj) {
            $arr_new[$obj->$field] = $obj;
        }
		return $arr_new;
	}

	# 6
	public function add($test=0)
    {
		$this->sql = "INSERT INTO " . $this->table() . " SET $this->set_";
		if ($test) _var::die($this);
		empty($this->vals)
			? self::query($this->sql)
            : self::query($this->sql, $this->vals);	
	}

	# 7
	public function upd()
    {
		$this->sql = "UPDATE " . $this->table() . " SET $this->set_";

		if ( ! empty($this->where)) {
			$this->sql .= $this->where;
		}

		empty($this->vals)
			? self::query($this->sql)
            : self::query($this->sql, $this->vals);	
	}

	# 9
	public function sav()
    {
		empty($this->where) ? $this->add() : $this->upd();
	}

	# 9
	public function del()
    {
		$this->sql = "DELETE FROM " . $this->table();

		if ( ! empty($this->where)) {
			$this->sql .= $this->where;
		}

		empty($this->vals)
			? self::query($this->sql)
            : self::query($this->sql, $this->vals);	
	}

	# 10
	public function byArray($parents, $by='', $test=0)
	{
		$by = empty($by) ? $this->table_ . '_idu' : $by;

		$keys = $vals = [];
		foreach ($parents as $par) {
			$keys[] = '?';
			$vals[] = $par->idu;
		}
		$keys = implode(', ', $keys);

		if ( ! $keys) {
			return [];
		}

		$sql = "SELECT * FROM $this->table_";
		$sql .= empty($this->where)
			? " WHERE $by IN ($keys)"
			: $this->where . " AND $by IN ($keys)";

		$merge = array_merge($this->vals??[], $vals);

		if ($test) {
			_var::die([$this, $sql, $vals]);
		}

		$rows = self::all($sql, $merge);
		$arr = [];
		foreach ($rows as $obj) {
			$arr[$obj->$by] = empty($arr[$obj->$by]) 
				? $obj : [$obj];
		}
		return $arr;
	}
}
