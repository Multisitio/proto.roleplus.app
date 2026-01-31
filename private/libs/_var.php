<?php

/**
 */
class _var
{
    #
    public static function return($var = '', $no_tags = 0)
    {
        if (is_array($var)) {
            array_walk_recursive($var, function (&$value) use ($no_tags) {
                if (is_bool($value)) {
                    $value = ($value === TRUE) ? 'TRUE' : 'FALSE';
                } elseif ($no_tags) {
                    $value = str_replace('<', '&lt;', $value);
                }
            });
        } elseif (is_bool($var)) {
            $var = ($var === TRUE) ? 'TRUE' : 'FALSE';
        } elseif (is_null($var)) {
            $var = 'NULL';
        } elseif ($no_tags) {
            $var = str_replace('<', '&lt;', $var);
        }
        return '<pre>' . print_r($var, true) . '</pre>';
    }

    #
    public static function echo($var = '', $no_tags = 0)
    {
        echo self::return($var, $no_tags);
    }

    #
    public static function die($var = '', $no_tags = 0)
    {
        $allowed_IP = Config::get('exception.trustedIp');
        if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_IP)) {
            return;
        }
        echo round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 4) . ' ms<br>';
        echo ($var) ? '<h3>RESULT</h3>' : '<h3>EMPTY</h3>';
        self::echo($var, $no_tags);
        die;
    }

    #
    static public function flush($str = '')
    {
        if (!ob_get_level()) {
            ob_start();
        }
        if ($str) {
            _var::echo([$str, '<script>window.scrollTo({left:0, top:document.body.scrollHeight, behavior:"smooth"});</script>']);
        }
        flush();
        ob_end_flush();
    }
}
