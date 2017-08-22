<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use XMLReader;

class HighscoreRequest extends UrlRequest {

    function parseResults($results)
    {
        $data = [
            'last_update' => 0,
            'highscore' => []
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
                            $ships = (int) $xml->getAttribute('ships'); //null? => 0
                            $data['highscore'][] = (object) [
                                'entity_id' => $xml->getAttribute('id'),
                                //'category' => 1,
                                'position' => ((int) $xml->getAttribute('position')),
                                'score' => ((int) $xml->getAttribute('score') ),
                                'ships' => $ships
                            ];
                        } elseif($xml->name=='alliance'){
                            $data['highscore'][] = (object) [
                                'entity_id' => $xml->getAttribute('id'),
                                //'category' => 2,
                                'position' => ((int) $xml->getAttribute('position')),
                                'score' => ((int) $xml->getAttribute('score') ),
                                'ships' => 0
                            ];
                        } elseif($xml->name=='highscore'){
                            $data['last_update'] = $xml->getAttribute('timestamp');
                        }
                        break;
                    case XMLReader::TEXT:
                    case XMLReader::CDATA: continue;
                }
            }
            unset($xml);
        } else {
            throw new \Exception("Highscore - Could not parse remote XML");
        }

        $errors = \libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Highscore - Invalid XML returned by server. Error Code:".$errors[0]->code);
        }
        return $data;
    }
}