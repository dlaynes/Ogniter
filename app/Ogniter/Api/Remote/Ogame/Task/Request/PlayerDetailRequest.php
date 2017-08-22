<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use DOMDocument;

class PlayerDetailRequest extends UrlRequest {

    function parseResults($results){
        $data = [
            'player' => NULL,
            'planets' => [],
            'positions' => [],
            'alliance' => NULL,
            'last_update' => 0
        ];

        \libxml_use_internal_errors(\TRUE);
        $dom = new DOMDocument();
        @$dom->loadXML($results);

        $playerData = $dom->getElementsByTagName('$playerData')->item(0);
        if(!$playerData){
            throw new \Exception("Player Detail - Invalid server XML");
        }
        $data['last_update'] = $playerData->attributes->getNamedItem('timestamp')->nodeValue;

        $status = $playerData->attributes->getNamedItem('status');

        $data['player'] = [
            'player_id' => $playerData->attributes->getNamedItem('id')->nodeValue,
            'name' => $playerData->attributes->getNamedItem('name')->nodeValue,
            'status' => $status ? $status->nodeValue : ''
        ];

        $planets = $dom->getElementsByTagName('planets')->item(0);
        $positions = $dom->getElementsByTagName('positions')->item(0);
        $alliance = $dom->getElementsByTagName('alliance')->item(0);

        foreach($positions->childNodes as $node){
            $ships = $node->attributes->getNamedItem('ships');
            $position = [
                'type' => $node->attributes->getNamedItem('type')->nodeValue,
                'score' => $node->attributes->getNamedItem('score')->nodeValue,
                'ships' => $ships ? $ships->nodeValue : 0
            ];
            $data['positions'][] = $position;
        }

        foreach($planets->childNodes as $node){

            $planet_id = $node->attributes->getNamedItem('id')->nodeValue;
            $coords = explode(':', $node->attributes->getNamedItem('coords')->nodeValue);
            $moon = $node->childNodes->item(0);

            $planet = [
                'type' => 1,
                'id' => $planet_id,
                'name' => $node->attributes->getNamedItem('name')->nodeValue,
                'galaxy' => $coords[0],
                'system' => $coords[1],
                'position' => $coords[2],
                'size' => 0
            ];
            $data['planets'][] = $planet;

            if($moon){
                $the_moon = [
                    'type' => 2,
                    'id' => $moon->attributes->getNamedItem('id')->nodeValue,
                    'name' => $moon->attributes->getNamedItem('name')->nodeValue,
                    'galaxy' => $coords[0],
                    'system' => $coords[1],
                    'position' => $coords[2],
                    'size' => $moon->attributes->getNamedItem('size')->nodeValue
                ];
                $data['planets'][] = $the_moon;
            }
        }

        if($alliance){
            //It doesn't seem the XML has other info available.
            $data['alliance'] = [
                'alliance_id' => $alliance->attributes->getNamedItem('id')->nodeValue,
                'name' => $dom->getElementsByTagName('name')->item(0)->nodeValue,
                'tag' => $dom->getElementsByTagName('tag')->item(0)->nodeValue,
            ];
        }

        $errors = \libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Player detail - Invalid XML returned by server. Error Code:".$errors[0]->code);
        }

        return $data;
    }
}
