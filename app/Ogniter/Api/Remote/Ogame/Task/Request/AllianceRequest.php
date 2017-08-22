<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use XMLReader;

class AllianceRequest extends UrlRequest {

    function parseResults($results)
    {
        $data = [
            'last_update' => 0,
            'alliances' => []
        ];
        \libxml_use_internal_errors(\TRUE);
        $xml = new XMLReader();
        if($xml->XML($results, 'UTF-8')){
            while(@$xml->read()){
                switch($xml->nodeType){
                    case XMLReader::END_ELEMENT: continue;
                    case XMLReader::ELEMENT:
                        if($xml->name=='alliance'){
                            //We ignore the list of users!
                            //(for now)
                            //That info is already available on the player list xml

                            $data['alliances'][]= (object) [
                                'alliance_id' => $xml->getAttribute('id'),
                                'name' => $xml->getAttribute('name'),
                                'tag' => $xml->getAttribute('tag'),
                                'homepage' => $xml->getAttribute('homepage'),
                                'logo' => $xml->getAttribute('logo'),
                                'open' => $xml->getAttribute('open')
                            ];
                        } elseif($xml->name=='alliances') {
                            $data['last_update'] = $xml->getAttribute('timestamp');
                        }
                        break;
                    case XMLReader::TEXT:
                    case XMLReader::CDATA: continue;
                }
            }
            unset($xml);
        } else {
            throw new \Exception("Alliances - Could not parse remote XML");
        }

        $errors = \libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Alliances - Invalid XML returned by server. Error Code:".$errors[0]->code);
        }
        return $data;
    }
}