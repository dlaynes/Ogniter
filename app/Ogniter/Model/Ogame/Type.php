<?php

namespace App\Ogniter\Model\Ogame;

use App\Ogniter\Model\HashTable;


class Type extends HashTable {
    protected $records = [
        0 => ['id'=>0, 'slug'=>'total'],
        1 => ['id'=>1, 'slug'=>'economy'],
        2 => ['id'=>2, 'slug'=>'research'],
        3 => ['id'=>3, 'slug'=>'military'],
        4 => ['id'=>4, 'slug'=>'military-lost'],
        5 => ['id'=>5, 'slug'=>'military-built'],
        6 => ['id'=>6, 'slug'=>'military-destroyed'],
        7 => ['id'=>7, 'slug'=>'honor'],
    ];
}