<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use XMLReader;

class PlanetRequest extends UrlRequest {

    function parseResults($results)
    {
        $data = [
            'last_update' => 0,
            'planets' => []
        ];

        $current_player_id = 0;
        $current_coords = [];

        libxml_use_internal_errors(\TRUE);
        $xml = new XMLReader();
        if($xml->XML($results, 'UTF-8')){
            while(@$xml->read()){
                switch($xml->nodeType){
                    case XMLReader::END_ELEMENT: continue;
                    case XMLReader::ELEMENT:
                        //This works because the planet and moon data is already ordered and nested.
                        if($xml->name=='planet'){
                            $current_coords = explode(':', $xml->getAttribute('coords') );
                            $current_player_id = (int) $xml->getAttribute('player');

                            $data['planets'][] = (object) array(
                                'planet_id' => $xml->getAttribute('id'),
                                'name' => $xml->getAttribute('name'),
                                'player_id' => $current_player_id,
                                'galaxy' => $current_coords[0],
                                'system' => $current_coords[1],
                                'position' => $current_coords[2],
                                'type' => 1,
                                'size' => '0'
                            );

                        } elseif($xml->name=='moon'){
                            $data['planets'][] = (object) array(
                                'planet_id' => $xml->getAttribute('id'),
                                'name' => $xml->getAttribute('name'),
                                'player_id' => $current_player_id,
                                'galaxy' => $current_coords[0],
                                'system' => $current_coords[1],
                                'position' => $current_coords[2],
                                'type' => 2,
                                'size' => $xml->getAttribute('size')
                            );
                        } elseif($xml->name=='universe'){
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

        $errors = libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Planets - Invalid XML returned by server. Error Code:".$errors[0]->code);
        }
        return $data;
    }
}