<?php

namespace App\Ogniter\Tools\Strings;

class Encrypt {

    public static function urlBase64Encode($str){
        return strtr(
            base64_encode($str), array(
                '+' => '.',
                '=' => '-',
                '/' => '~'
            )
        );
    }

    public static function urlBase64Decode($str){
        return base64_decode(strtr(
            $str, array(
                '.' => '+',
                '-' => '=',
                '~' => '/'
            )
        ));
    }

    public static function arrayUrlEncode($arr, $url_encode=\FALSE){
        $params = array();
        foreach ($arr as $k => $v) {
            if ($v != '') {
                $reemp = str_replace(array('~', '-'), array('__DSPTS__', '__GUION__'), array($k, $v));
                $params[] = $reemp[0] . '~' . $reemp[1];
            }
        }
        if ($url_encode) {
            return self::urlBase64Encode(implode('-', $params));
        }
        return implode('-', $params);
    }

    public static function arrayUrlDecode($str, $url_decode=\FALSE){
        $res = array();
        if ($url_decode) {
            $kv = explode('-', self::urlBase64Decode($str));
        } else {
            $kv = explode('-', $str);
        }
        foreach ($kv as $f) {
            $r = explode('~', $f);
            if (isset($r[1])) {
                $replacement = str_replace(array('__DSPTS__', '__GUION__'), array('~', '-'), $r[1]);
                $res[$r[0]] = $replacement;
            }
        }
        return $res;
    }
}