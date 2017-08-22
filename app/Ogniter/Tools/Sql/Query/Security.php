<?php

namespace App\Ogniter\Tools\Sql\Query;

class Security {

    public static function escapeLike($s, $e) {
        return str_replace(array($e, '_', '%'), array($e.$e, $e.'_', $e.'%'), $s);
    }

}