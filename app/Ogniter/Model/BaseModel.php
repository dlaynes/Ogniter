<?php

namespace App\Ogniter\Model;

use \Cache;
use \DB;
use App\Ogniter\Tools\Sql\Query\Security;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

    static function cacheQuery($namespace, $callback, $seconds=86400, $debugMode=FALSE){
        if($debugMode){
            Cache::forget($namespace);
        }

        if (($result = Cache::get($namespace)) === NULL)
        {
            $result = $callback();
            if($result===NULL || $result===FALSE){
                return NULL;
            }
            Cache::put($namespace, $result, $seconds);
        }
        return $result;
    }

    //??????
    public static function newIfNotFoundCondition($field, $val){
        $row = static::where($field, '=', $val)->first();
        if(!$row) {
            return new static();
        }
        return $row;
    }

    function tagAutoComplete($key, $value, $fields='*', $where_extra=array()){

        $param = ['%'.Security::escapeLike($value,'=').'%'];

        return DB::select(
            "SELECT $fields FROM ".$this->table." WHERE $key LIKE ? ESCAPE '='".$where_extra, $param );
    }


}