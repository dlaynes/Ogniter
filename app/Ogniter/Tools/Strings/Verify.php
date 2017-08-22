<?php

namespace App\Ogniter\Tools\Strings;

class Verify {

    static function processDate($str){
        $fields = explode('-', str_replace('/', '-', $str));
        if(empty($fields[1]) || empty($fields[2]) ){
            return NULL;
        }
        if(!checkdate($fields[1], $fields[2], $fields[0])){
            return NULL;
        }
        return strtotime($str);
    }
}