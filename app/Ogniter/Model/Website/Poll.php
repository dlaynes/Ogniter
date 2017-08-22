<?php

namespace App\Ogniter\Model\Website;

use Illuminate\Database\Eloquent\Model;
use DB;

class Poll extends Model{

    protected $table_name = 'polls';

    public $timestamps = \FALSE;

    function getLatestPoll(){
        $poll = DB::selectOne("SELECT `id`, `question` FROM polls WHERE active=1");
        if(isset($poll->id) ){
            $poll->answers = DB::select(
                "SELECT `value`, `answer` FROM poll_answers WHERE poll_id=".$poll->id);
            return $poll;
        }
        return NULL;
    }

    function addVote($poll_id, $value){
        DB::statement(
            "UPDATE poll_answers SET votes=votes+1 WHERE poll_id=? AND `value`=?", array($poll_id, $value));
    }

    function disablePolls(){
        DB::statement("UPDATE polls SET active=0 WHERE 1");
    }

    function enablePoll($poll_id){
        DB::statement(
            "UPDATE polls SET active=1 WHERE id=?", array($poll_id));
    }

    function getPoll($poll_id){
        $poll_id = (int) $poll_id;
        return DB::selectOne("SELECT `id`, `question` FROM polls WHERE id=".$poll_id);
    }

    static function appendAnswer($poll_id, $value, $answer){
        $sql = "INSERT INTO poll_answers SET poll_id=?, value=?, answer=?, votes=0";
        DB::statement($sql,[$poll_id, $value, $answer]);
    }

    function getResults($poll_id){
        $poll = $this->getPoll($poll_id);
        if(isset($poll->id) ){
            $poll->answers = DB::select(
                "SELECT `answer`, `votes` FROM poll_answers WHERE poll_id=".$poll->id);
            return $poll;
        }
        return NULL;
    }

}