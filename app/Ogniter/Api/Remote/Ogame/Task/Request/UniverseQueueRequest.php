<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use XMLReader;

class UniverseQueueRequest extends UrlRequest {

    function parseResults($results){
        $data = [
            'last_update' => 0,
            'universes' => []
        ];

        libxml_use_internal_errors(\TRUE);
        $xml = new XMLReader();
        if($xml->XML($results, 'UTF-8')){
            while(@$xml->read()){
                switch($xml->nodeType){
                    case XMLReader::END_ELEMENT: continue;
                    case XMLReader::ELEMENT:
                        if($xml->name=='universe'){
                            $data['universes'][]= [
                                'number' => $xml->getAttribute('id'),
                                'url' => $xml->getAttribute('href')
                            ];
                        } elseif($xml->name=='universes') {
                            $data['last_update'] = $xml->getAttribute('timestamp');
                        }
                        break;
                    case XMLReader::TEXT:
                    case XMLReader::CDATA: continue;
                }
            }
            unset($xml);
        } else {
            throw new \Exception("Api Enabled Universes - Could not parse remote XML");
        }

        $errors = libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Api Enabled Universes - Invalid XML returned by server. ".$errors[0]);
        }

        return $data;
    }

}