<?php
/**
 */
class _check
{
    #
	static public function val($values, $default, $input)
	{
        foreach ($values as $val) {
            if ($val == $input) {
                return $val;
            }
        }
        return $default;
    }
}
