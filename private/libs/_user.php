<?php
/**
 */
class _user
{
	public static function content($content)
	{
        ob_end_clean();
        return $content;
    }

    #
    public static function avatar($usuario, $size=90)
	{
		ob_start();
		?>
		<a class="avatar size-<?=$size?>" href="/usuarios/<?=$usuario->slug?>"><?=_img::src("/img/usuarios/$usuario->idu", $usuario->avatar, width: $size, height: $size)?></a>
		<?php
		return self::content(ob_get_contents());
	}
}
