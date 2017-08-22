<?php

namespace App\Ogniter\Api\Remote\Ogame\Task\Request;

use App\Ogniter\Api\Remote\Base\Task\Request\UrlRequest;
use \DOMDocument;

class UniverseRequest extends UrlRequest {

    function parseResults($results){

        \libxml_use_internal_errors(\TRUE);
        $dom = new DOMDocument();
        @$dom->loadXML($results);

        $serverData = $dom->getElementsByTagName('serverData')->item(0);
        if(!$serverData){
            throw new \Exception("Universe - Invalid server XML");
        }
        $last_xml_update = $serverData->attributes->getNamedItem('timestamp')->nodeValue;
        $og_server_id = $serverData->attributes->getNamedItem('serverId')->nodeValue;
        if(!$og_server_id){
            throw new \Exception("Universe - Empty Ogame Server ID in XML");
        }

        $name = $dom->getElementsByTagName('name')->item(0);
        $number = $dom->getElementsByTagName('number')->item(0);
        $fields = $dom->getElementsByTagName('bonusFields')->item(0);
        $speed_fleet = $dom->getElementsByTagName('speedFleet')->item(0);
        $speed = $dom->getElementsByTagName('speed')->item(0)->nodeValue;

        $donut_galaxy = $dom->getElementsByTagName('donutGalaxy')->item(0);
        $donut_system = $dom->getElementsByTagName('donutSystem')->item(0);

        //Wreckfields
        $wf_enabled = $dom->getElementsByTagName('wfEnabled')->item(0);
        $wf_minimun_res_lost = $dom->getElementsByTagName('wfMinimumRessLost')->item(0);
        $wf_minimun_loss_perc = $dom->getElementsByTagName('wfMinimumLossPercentage')->item(0);
        $wf_basic_percentage_repair = $dom->getElementsByTagName('wfBasicPercentageRepairable')->item(0);

        //New Debris value
        $debris_factor_def = $dom->getElementsByTagName('debrisFactorDef')->item(0);

        //New Deuterium usage value
        $global_deuterium_save_factor = $dom->getElementsByTagName('globalDeuteriumSaveFactor')->item(0);

        //Does not include local_name and weight
        $universe = (object) [
            'ogame_id' => $og_server_id,
            'name' => $name ? $name->nodeValue : 'Server '.($number ? $number->nodeValue : 0),
            'number' => $number ? $number->nodeValue : 0,
            'language' => $dom->getElementsByTagName('language')->item(0)->nodeValue,
            'timezone' => $dom->getElementsByTagName('timezone')->item(0)->nodeValue,
            'domain' => $dom->getElementsByTagName('domain')->item(0)->nodeValue,
            'version' => $dom->getElementsByTagName('version')->item(0)->nodeValue,
            'speed' => $speed,
            'speed_fleet' => $speed_fleet? $speed_fleet->nodeValue : $speed,
            'galaxies' => $dom->getElementsByTagName('galaxies')->item(0)->nodeValue,
            'systems' => $dom->getElementsByTagName('systems')->item(0)->nodeValue,
            'acs' => $dom->getElementsByTagName('acs')->item(0)->nodeValue,
            'rapid_fire' => $dom->getElementsByTagName('rapidFire')->item(0)->nodeValue,
            'def_to_debris' => $dom->getElementsByTagName('defToTF')->item(0)->nodeValue,
            'debris_factor' => $dom->getElementsByTagName('debrisFactor')->item(0)->nodeValue,
            'repair_factor' => $dom->getElementsByTagName('repairFactor')->item(0)->nodeValue,
            'newbie_protection_limit' => $dom->getElementsByTagName('newbieProtectionLimit')->item(0)->nodeValue,
            'newbie_protection_high' => $dom->getElementsByTagName('newbieProtectionHigh')->item(0)->nodeValue,
            'top_score' => $dom->getElementsByTagName('topScore')->item(0)->nodeValue,
            'extra_fields' => ($fields)? $fields->nodeValue : 0,
            'last_update' => $last_xml_update,
            'donut_galaxy' => $donut_galaxy? $donut_galaxy->nodeValue : 0,
            'donut_system' => $donut_system? $donut_system->nodeValue : 0,
            'wf_enabled' => $wf_enabled? (int) $wf_enabled->nodeValue : 0,
            'wf_minimun_res_lost' => $wf_minimun_res_lost? (int) $wf_minimun_res_lost->nodeValue : 0,
            'wf_minimun_loss_perc' => $wf_minimun_loss_perc? (int) $wf_minimun_loss_perc->nodeValue : 0,
            'wf_basic_percentage_repair' => $wf_basic_percentage_repair? (int) $wf_basic_percentage_repair->nodeValue : 0,
            'debris_factor_def' => $debris_factor_def ? (string) $debris_factor_def->nodeValue : 0.3,
            'global_deuterium_save_factor' => $global_deuterium_save_factor ? (string) $global_deuterium_save_factor->nodeValue : 0.5
        ];
        unset($dom);

        $errors = \libxml_get_errors();
        if(count($errors)){
            throw new \Exception("Universe detail - Invalid XML returned by server. Error Code:".$errors[0]->code);
        }

        return $universe;
    }
}
