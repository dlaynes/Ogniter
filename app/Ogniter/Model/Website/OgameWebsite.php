<?php

namespace App\Ogniter\Model\Website;

use Illuminate\Database\Eloquent\Model;
use DB;

class OgameWebsite extends Model {

    protected $table = "sites";

    public $timestamps = \FALSE;

    function getSites($order_by, $order='DESC'){

        if(!in_array($order_by, array('id','score','votes'))){ $order_by = 'score'; }
        if($order!='DESC') { $order='ASC'; }

        return DB::select(
            "SELECT id,name,description,image,url,votes, score FROM sites ORDER BY $order_by $order"
        );
    }

}