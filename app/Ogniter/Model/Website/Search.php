<?php

namespace App\Ogniter\Model\Website;

use Illuminate\Database\Eloquent\Model;
use DB;

class Search extends Model {

    protected $table = 'searches';

    function register($text, $add=FALSE, $universe_id=0){
        $text = trim($text);
        if(empty($text)){
            return;
        }
        $text = substr($text, 0, 80);

        $universe_id = (int) $universe_id;

        $filter = str_slug($text);

        $r = DB::selectOne("SELECT id FROM searches WHERE universe_id=$universe_id AND slug=?", [$filter]);

        $last_update = time();

        if(!isset($r->id) ){
            $sql = "INSERT INTO searches (universe_id, `text`, slug, repeated, last_update)
              VALUES (?,?,?,1,?)";
            DB::statement($sql, [$universe_id,$text,$filter, $last_update]);
        } elseif($add){
            DB::statement("UPDATE searches SET repeated=repeated+1,last_update=$last_update WHERE id=".$r->id);
        }
    }

    function mostPopular($universe_id=0){
        $universe_id = (int) $universe_id;
        return DB::select(
            "SELECT `text`, repeated FROM searches WHERE universe_id=".$universe_id." order BY repeated DESC LIMIT 10");
    }
}