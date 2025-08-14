<?php

namespace App;

use DateTime;

class Utillity
{
    
    public static function th2dbDate($str)
    {
        if ($str) {
            $date = DateTime::createFromFormat('d/m/Y', $str);
            return  $date->format('Y-m-d');
        }
    }

    public static function db2thDate($str)
    {
        if ($str) {
            $date = DateTime::createFromFormat('Y-m-d', $str);
            return  $date->format('d/m/Y');
        }
    }

    public static function BE2AD($str)
    {
        if ($str) {
            $date = explode('-',$str);
            $date[0] = $date[0] - 543;
            return   implode('-',$date);
        }
    }

    public static function AD2BE($str)
    {
        if ($str) {
            $date = explode('-',$str);
            $date[0] = $date[0] + 543;
            return   implode('-',$date);
        }
    }

    public static function age2BirthBE($age)
    {
        if ($age) {
            return  date("Y") - $age + 543;;
        }
    }

}
