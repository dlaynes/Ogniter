<?php

namespace App\Ogniter\ViewHelpers;

class Tags {

    protected $trans;

    protected $rules = [];
    
    public function generateLanguageSettings($trans){
        $this->trans = $trans;
        $this->buildRules();
    }

    protected function buildRules(){
        $this->rules = [
            'past_str' => $this->trans->trans('ogniter.n_time_ago'),
            'future_str' => $this->trans->trans('ogniter.in_n_time'),
            'formats' => [
                31536000 => array($this->trans->trans('ogniter.year'),$this->trans->trans('ogniter.years')),
                2592000 => array($this->trans->trans('ogniter.month'),$this->trans->trans('ogniter.months')),
                86400 => array($this->trans->trans('ogniter.day'),$this->trans->trans('ogniter.days')),
                3600 => array($this->trans->trans('ogniter.hour'),$this->trans->trans('ogniter.hours')),
                60 => array($this->trans->trans('ogniter.minute'),$this->trans->trans('ogniter.minutes')),
                1 => [$this->trans->trans('ogniter.second'),$this->trans->trans('ogniter.seconds')]
            ]
        ];
    }

    public function parseTime($difference, $past=TRUE){
        $language = $this->rules;

        if ($difference < 1) {
            return $past ?
                '<span class="label label-info">'.str_replace('%t%', '0 '.$language['formats'][1][1], $language['past_str']).'</span>' :
                '<span class="label label-info">'.str_replace('%t%', '0 '.$language['formats'][1][1], $language['future_str']).'</span>';
        }

        $res = '';

        foreach ($language['formats'] as $secs => $arr) {
            $d = $difference / $secs;
            if ($d >= 1) {
                $r = round($d);
                if($r > 1){
                    $res = $r.' '.$arr[1]; //plural
                } else{
                    $res = $r.' '.$arr[0]; //singular
                }
                break;
            }
        }
        if($past){
            $res = str_replace('%t%', $res, $language['past_str']);
        } else{
            $res = str_replace('%t%', $res, $language['future_str']);
        }

        if($difference < 3601) {
            return '<span class="label label-info">'.$res.'</span>';
        } elseif($difference < 21607) {
            return '<span class="label label-success">'.$res.'</span>';
        } elseif($difference < 86401) {
            return '<span class="label label-warning">'.$res.'</span>';
        } elseif($difference < 604801) {
            return '<span class="label label-important">'.$res.'</span>';
        } else {
            return '<span class="label">'.$res.'</span>';
        }
    }

    public static function parseDifference($difference){
        if(is_null($difference) ){
            return '';
        }
        if($difference>=0){
            return '<span class="text-success">+'.number_format($difference).'</span>';
        } else{
            return '<span class="text-error">'.number_format($difference).'</span>';
        }

    }

}