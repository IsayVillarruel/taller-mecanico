<?php

namespace App\CustomClass;

class Tools {

    function generateString($length)
    {
        $string = "";
        $possible = "0123456789bcdfghjkmnpqrstvwxyz";
        $i = 0;
        while ($i < $length) {
          $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
          $string .= $char;
          $i++;
        }
        return $string; 
    }

}