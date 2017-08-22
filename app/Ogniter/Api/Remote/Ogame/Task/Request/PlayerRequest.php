<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use XMLReader;

class PlayerRequest extends UrlRequest {

    function parseResults($results)
    {
        $data = [
            'last_update' => 0,
            'players' => []
        ];

        \libxml_use_internal_errors(\TRUE);
        $xml = new XMLReader();
        if($xml->XML($results, 'UTF-8')){
            while(@$xml->read()){
                switch($xml->nodeType){
                    case XMLReader::END_ELEMENT: continue;
                    case XMLReader::ELEMENT:
                        //most probable option goes first
                        if($xml->name=='player'){
                            $data['players'][]= (object) [
                                'player_id' => (int) $xml->getAttribute('id'),
                                'alliance_id' => (int) $xml->getAttribute('alliance'),
                                'name' => $xml->getAttribute('name'),
                                'raw_status' => $xml->getAttribute('status')
                            ];
                        } elseif($xml->name=='players') {
                            $data['last_update'] = $xml->getAttribute('timestamp');
                        }
                        break;
                    case XMLReader::TEXT:
                    case XMLReader::CDATA: continue;
                }
            }
            unset($xml);
        } else {
            throw new \Exception("Could not parse remote XML");
        }

        $errors = \libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Players - Invalid XML returned by server. Error Code:".$errors[0]->code);
        }
        return $data;
    }
}