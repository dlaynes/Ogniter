<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use DOMDocument;

class LocalizationRequest extends UrlRequest
{

    function parseResults($results)
    {
        $data = [
            'techs' => [],
            'missions' => [],
            'last_update' => 0
        ];

        \libxml_use_internal_errors(\TRUE);
        $dom = new DOMDocument();
        @$dom->loadXML($results);

        $localization = $dom->getElementsByTagName('localization')->item(0);
        if (!$localization) {
            throw new \Exception("Localization - Invalid server XML");
        }
        $data['last_update'] = $localization->attributes->getNamedItem('timestamp')->nodeValue;

        $techs = $dom->getElementsByTagName('techs')->item(0);
        $missions = $dom->getElementsByTagName('missions')->item(0);

        foreach ($techs->childNodes as $node) {
            $tech = [
                'id' => $node->attributes->getNamedItem('id')->nodeValue,
                'translation' => $node->nodeValue
            ];
            $data['techs'][] = $tech;
        }

        foreach ($missions->childNodes as $node) {
            $mission = [
                'id' => $node->attributes->getNamedItem('id')->nodeValue,
                'translation' => $node->nodeValue
            ];
            $data['missions'][] = $mission;
        }

        $errors = \libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Players - Invalid XML returned by server. Error Code:".$errors[0]->code);
        }

        return $data;

    }
}