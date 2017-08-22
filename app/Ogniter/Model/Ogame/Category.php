<?php

namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\HashTable;


class Category extends HashTable {
    protected $records = [
        1 => ['id'=>1, 'slug'=>'player'],
        2 => ['id'=>2, 'slug'=>'alliance']
    ];
}