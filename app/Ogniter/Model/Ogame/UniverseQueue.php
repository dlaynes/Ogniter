<?php
namespace App\Ogniter\Model\Ogame;

use Illuminate\Database\Eloquent\Model;

class UniverseQueue extends Model {

    protected $table = "server_queue";

    protected $primaryKey = "id";

    public $timestamps = false;

}
