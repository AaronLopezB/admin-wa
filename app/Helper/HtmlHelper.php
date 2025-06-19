<?php

namespace App\Helper;

class HtmlHelper
{
    public static function generateOptionType($options, $placeholder)
    {
        $html = "<option value='0'>{$placeholder}</option>";
        foreach ($options as $option) {
            $html .= "<option value='$option->id'>$option->nombre</option>";
        }

        return $html;
    }
}
