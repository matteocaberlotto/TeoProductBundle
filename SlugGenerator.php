<?php

namespace Teo\ProductBundle;

class SlugGenerator
{
    public static function generate($string, $inc = 0)
    {
        // replace non letter or digits by -
        $string = preg_replace('~[^\\pL\d]+~u', '-', $string);

        // trim
        $string = trim($string, '-');

        // transliterate
        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

        // lowercase
        $string = strtolower($string);

        // remove unwanted characters
        $string = preg_replace('~[^-\w]+~', '', $string);

        if ($inc) {
            $string .= '-' . $inc;
        }

        return $string;
    }
}
