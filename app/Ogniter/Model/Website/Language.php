<?php

namespace App\Ogniter\Model\Website;

use App\Ogniter\Model\HashTable;

class Language extends HashTable {

    protected $records = array(
        'es' => ['flag'=>'es', 'desc' => 'Español', 'slug'=>'spanish' ],
        'en' => ['flag'=>'gb', 'desc'=> 'English', 'slug'=>'english' ],
        'de' => ['flag'=>'de', 'desc' => 'Deutsch', 'slug'=>'german' ],
        'fr' => ['flag'=>'fr', 'desc' => 'Français', 'slug'=>'french' ],
        'ru' => ['flag'=>'ru', 'desc' => 'русский', 'slug'=>'russian' ],
        'pt' => ['flag'=>'pt', 'desc' => 'Português', 'slug'=>'portuguese' ],
        'pl' => ['flag'=>'pl', 'desc'=>'Polski', 'slug'=>'polish' ],
        'tr' => ['flag'=>'tr', 'desc' => 'Türkçe', 'slug'=>'turkish' ],
        'it' => ['flag'=>'it','desc'=>'Italiano', 'slug'=>'italian' ],
        'ro' => ['flag'=>'ro', 'desc' =>'Romanian', 'slug'=>'romanian' ],
        //'cz' => ['flag'=>'cz','desc'=>'čeština', 'slug'=>'czech' ],
    );

    function getDefaultLanguageCode()
    {
        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return 'en';
        }
        $code = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
        if(isset($this->values[$code])){
            return $code;
        }
        return 'en';
    }
}