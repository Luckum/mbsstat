<?php

namespace app\helpers;

class Utils
{
    public static function unique_multidim_array($array, $key)
    {
        $temp_array = $key_array = [];
        $i = 0;
       
        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}