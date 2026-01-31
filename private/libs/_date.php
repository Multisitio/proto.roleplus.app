<?php
/**
 */
class _date
{
    #
	static public function ago($date)
	{
        $now = new DateTime();
        $pub = new DateTime($date);
        $dif = $now->diff($pub);
        $s = '';
        if ($dif->y > 0) $s .= "$dif->y a";
        elseif ($dif->m > 0) $s .= "$dif->m m";
        elseif ($dif->d > 0) $s .= "$dif->d d";
        elseif ($dif->h > 0) $s .= "$dif->h h";
        elseif ($dif->i > 0) $s .= "$dif->i'";
        elseif ($dif->s > 0) $s .= "$dif->s\"";
        return $s;
    }
}
