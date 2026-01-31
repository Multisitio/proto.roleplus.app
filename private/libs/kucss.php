<?php
/**
 */
class Kucss extends _html
{
    # 2
    public static function input($name, $args=[])
    {
        $args['type'] = $args['type'] ?? 'text';
        $args['name'] = $name;
        $args['label'] = $args['label'] ?? ucfirst($name);
        $args['placeholder'] = '';
        $content[] = self::tag('span', $args['label']);
        $content[] = self::openTag('input', $args);
        return self::tag('label', $content);
    }
}
