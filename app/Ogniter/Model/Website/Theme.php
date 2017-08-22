<?php

namespace App\Ogniter\Model\Website;

use App\Ogniter\Model\HashTable;

class Theme extends HashTable {
    protected $records = [
        'cerulean' => array('type'=>'light','desc'=>'Cerulean','name'=>'cerulean'),
        'slate' => array('type'=>'dark', 'desc'=>'Slate','name'=>'slate'),
        'united' => array('type'=>'light','desc'=>'United','name'=>'united'),
        'journal' => array('type'=>'light','desc'=>'Journal','name'=>'journal'),
        'redy' => array('type'=>'light','desc'=>'Redy','name'=>'redy'),
        'cyborg' => array('type'=>'dark','desc'=>'Cyborg','name'=>'cyborg'),
        'spacelab' => array('type'=>'light','desc'=>'Spacelab','name'=>'spacelab'),
        'classic' => array('type'=>'light','desc'=>'Classic','name'=>'classic'),
        'simplex' => array('type'=>'light','desc'=>'Simplex', 'name'=>'simplex'),
    ];
}