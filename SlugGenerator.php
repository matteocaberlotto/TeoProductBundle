<?php

namespace Teo\ProductBundle;

class SlugGenerator
{
    public static function generate($string, $inc = 0)
    {
        $slug = strtolower($string);
        $slug = preg_replace('/[^a-z]+/', ' ', $slug);
        $slug = trim($slug);
        $slug = preg_replace('/[\s]+/', '-', $slug);

        if ($inc) {
            $slug .= '-' . $inc;
        }

        return $slug;
    }
}