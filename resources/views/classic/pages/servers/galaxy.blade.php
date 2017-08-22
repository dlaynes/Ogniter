<?php
$a = array();
$a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_galaxy');
$a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_galaxy');

$a = str_replace(array('%server%','%domain%', '%location%'),
        array($currentUniverse->local_name, $currentCountry->domain, $galaxy.':'.$system), $a);
?>
@extends('classic.layouts.default')
@section('title'){{ $a['PAGE_TITLE'] }}@endsection
@section('description'){{ $a['PAGE_DESCRIPTION'] }}@endsection

@section('head')
    <style>
        #cboxLoadedContent > div { width: 560px !important; }
        #colorbox{width: 580px !important; padding-right: 0 !important; }
        #cboxContent { background-color: transparent; }
    </style>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
        <li>
            <a href="{{ $currentCountry->language }}">
                <i class="flag flag-{{ $currentCountry->flag }}"></i>
                {{ $currentCountry->domain }}</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $uniShortCode }}">{{ $currentUniverse->local_name }}</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $currentPath }}">{{ $lang->trans('ogniter.og_galaxy_view').' ['.$galaxy.':'.$system.']' }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $currentUniverse->local_name.' - '.$lang->trans('ogniter.og_galaxy_view').' ['.$galaxy.':'.$system.']' }}</h2>
            </div>
            <div class="box-content above-me">

                <?php
                $count = count($planet_data['planets']);
                if($count == 15){
                    $class = ' label-important';
                } elseif($count> 10){
                    $class = ' label-warning';
                } elseif($count> 5){
                    $class = '';
                } elseif($count> 1) {
                    $class = ' label-info';
                } else{
                    $class = ' label-success';
                }

                if($player_count > 251){
                    $low_250 = $player_count - 251;
                } else{
                    $low_250 = 0;
                }
                if($player_count > 101){
                    $low_100 = $player_count - 101;
                } else{
                    $low_100 = 0;
                }
                if($player_count > 11){
                    $low_10 = $player_count - 11;
                } else{
                    $low_10 = 0;
                }
                $dif = time() - $last_update;

                if($currentUniverse->api_enabled){ ?>
                <div class="pull-left">
                    <?php echo $lang->trans('ogniter.last_update'),' (',$lang->trans('ogniter.planets'),'): ', $tagsHelper->parseTime($dif, TRUE)?>
                </div>
                <div class="pull-right">
                    <?php echo $lang->trans('ogniter.next_update'),': ', $tagsHelper->parseTime(86400*7 - $dif, FALSE)?>
                </div>
                <p>&nbsp;</p>
                <hr />
                <?php } ?>
                {!! Form::open(array('url' => $uniShortCode.'/galaxy', 'class="form-inline"')) !!}
                    <div class="galaxy-container">
                        <div class="galaxy-view clearfix" id="galaxy_view">
                            <div class="galaxy-page">
                                <ul class="pager">
                                    <li class="previous">
                                        <a href="<?php echo $uniShortCode?>/galaxy/<?php echo $galaxy,'/',$prev_system?>"
                                           class="galaxy-change">&larr; [<?php echo $galaxy,':',$prev_system?>]</a>
                                    </li>
                                    <li>
                                        <label for="galaxy"><?php echo $lang->trans('ogniter.og_galaxy')?>:</label>
                                        <select class="input-mini input-sm" name="galaxy" id="galaxy">
                                            <?php
                                            $limit = $currentUniverse->galaxies+1;
                                            for($i=1; $i < $limit; $i++){ ?>
                                            <option value="<?php echo $i?>" <?php if($galaxy==$i){ echo 'selected="selected"';}?>><?php echo $i?></option>
                                            <?php } ?>
                                        </select>
                                        <label for="system"><?php echo $lang->trans('ogniter.og_system')?>:</label>
                                        <input type="text" class="input-mini input-sm" name="system" id="system" value="<?php echo $system?>" placeholder="<?php echo $lang->trans('ogniter.og_system')?>" />
                                        <input type="text" name="name_hddn" value="" class="hide" style="display:none" />
                                        <input type="submit" class="submit btn btn-warning btn-small btn-xs" value="<?php echo $lang->trans('ogniter.og_send')?>" />
                                    </li>
                                    <li class="next">
                                        <a href="<?php echo $uniShortCode?>/galaxy/<?php echo $galaxy,'/',$next_system?>" class="galaxy-change">[<?php echo $galaxy,':',$next_system?>] &rarr;</a>
                                    </li>
                                </ul>
                                <table class="table table-striped table-bordered table-condensed table-hover text-center">
                                    <thead>
                                    <tr>
                                        <th class="gal-pos"><?php echo $lang->trans('ogniter.og_location')?></th>
                                        <th class="gal-pl text-center"><?php echo $lang->trans('ogniter.og_planet')?></th>
                                        <th class="gal-mn text-center"><?php echo $lang->trans('ogniter.og_moon')?></th>
                                        <th class="gal-ply"><i class="icon-user"></i> <?php echo $lang->trans('ogniter.og_player_status')?></th>
                                        <th class="gar-rk"><i class="icon-list-alt"></i> <?php echo $lang->trans('ogniter.og_position')?></th>
                                        <th class="gal-ally"><i class="icon-screenshot"></i> <?php echo $lang->trans('ogniter.og_alliance')?></th>
                                        <th class="text-center" style="width: 80px"><i class="icon-time"></i> <?php echo $lang->trans('ogniter.og_flight_times')?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $pos = $galaxy.':'.$system;
                                    for($i=1; $i< 16; $i++){
                                    $ps = 0;
                                        ?>
                                    <tr>
                                        <td class="gal-pos">[<?php echo $pos.':'.$i?>]</td>
                                        <td class="gal-pl text-left"><?php if(isset($planet_data['planets'][$i])){
                                                echo '<i class="icon-globe"></i> &nbsp;';
                                                echo ($planet_data['planets'][$i]->change_count < 2 ) ?
                                                        e($planet_data['planets'][$i]->name) :
                                                        '<a class="colorbox" href="'.$uniShortCode.'/ajax_planet/'. $planet_data['planets'][$i]->planet_id.'">'.e($planet_data['planets'][$i]->name).'</a>';
                                            } ?>
                                        </td>
                                        <td class="gal-mn text-left"><?php if(isset($planet_data['moons'][$i])){
                                                echo '<a href="javascript:;" data-size="'.$planet_data['moons'][$i]->size.'"><i class="icon-adjust"></i></a> &nbsp;';
                                                echo ($planet_data['moons'][$i]->change_count < 2 ) ? e($planet_data['moons'][$i]->name) :
                                                        '<a class="moon colorbox" href="'.$uniShortCode.'/ajax_planet/'. $planet_data['moons'][$i]->planet_id.'">'.e($planet_data['moons'][$i]->name).'</a>';
                                                echo  '&nbsp;(',$planet_data['moons'][$i]->size,' km)';
                                            } ?>
                                        </td>
                                        <?php if(isset($planet_data['planets'][$i])){ ?>
                                        <td class="gal-ply text-left"><?php if($planet_data['planets'][$i]->player_id) { ?>
                                            <a href="<?php echo $currentUniverse->language.'/'.$currentUniverse->id?>/player/<?php echo $planet_data['planets'][$i]->player_id?>">
                                                {{ $planet_data['planets'][$i]->player_name }}
                                            </a> <?php echo $planet_data['planets'][$i]->player_status ? '('.$planet_data['planets'][$i]->player_status_string.')' : '' ?>
                                            <?php
                                            if( $planet_data['planets'][$i]->player_name && strpos($planet_data['planets'][$i]->player_status_string, 'i') ===FALSE
                                                    && strpos($planet_data['planets'][$i]->player_status_string, 'I')===FALSE &&
                                                    strpos($planet_data['planets'][$i]->player_status_string, 'a') ===FALSE )
                                            {

                                                $honor_position = (int) $planet_data['planets'][$i]->honor_position;
                                                $honor_score = (int) $planet_data['planets'][$i]->honor_score;
                                                //Is he an emperor?
                                                if($honor_position < 11 && $honor_score > 14999 ){
                                                    echo ' &nbsp; <a href="',$uniShortCode,'/track/bandits-emperors/',$galaxy,'/5"><i class="icon-star-empty" title="',$lang->trans('ogniter.grand_emperor'),'"></i></a>';
                                                } elseif($honor_position < 101 && $honor_score > 2499){
                                                    echo ' &nbsp; <a href="',$uniShortCode,'/track/bandits-emperors/',$galaxy,'/5"><i class="icon-star-empty" title="',$lang->trans('ogniter.emperor'),'"></i></a>';
                                                } elseif($honor_position < 251 && $honor_score > 499){
                                                    echo ' &nbsp; <a href="',$uniShortCode,'/track/bandits-emperors/',$galaxy,'/5"><i class="icon-star-empty" title="',$lang->trans('ogniter.star_lord'),'"></i></a>';
                                                }
                                                //Is he a bandit??
                                                elseif($honor_position > $low_10 && $honor_score < -14999){
                                                    echo ' &nbsp; <a href="',$uniShortCode,'/track/bandits-emperors/',$galaxy,'/2"><i class="icon-star" title="',$lang->trans('ogniter.bandit_king'),'"></i></a>';
                                                }
                                                elseif($honor_position > $low_100 && $honor_score < -2499){
                                                    echo ' &nbsp; <a href="',$uniShortCode,'/track/bandits-emperors/',$galaxy,'/2"><i class="icon-star" title="',$lang->trans('ogniter.bandit_lord'),'"></i></a>';
                                                }
                                                elseif($honor_position > $low_250 && $honor_score < -499){
                                                    echo ' &nbsp; <a href="',$uniShortCode,'/track/bandits-emperors/',$galaxy,'/2"><i class="icon-star" title="',$lang->trans('ogniter.bandit'),'"></i></a>';
                                                }
                                            } ?>
                                            <?php }
                                            $page = floor($planet_data['planets'][$i]->ranking_position/100 ) + 1;
                                            ?>
                                        </td>
                                        <td class="gal-rk"><?php echo '<a href="',$uniShortCode,'/highscore/players/0?page=',$page,'#player',$planet_data['planets'][$i]->player_id,'">',$planet_data['planets'][$i]->ranking_position,'</a>'?></td>
                                        <td class="gal-ally"><?php if($planet_data['planets'][$i]->alliance_id){ ?>
                                            <a href="<?php echo $uniShortCode,'/alliance/', $planet_data['planets'][$i]->alliance_id?>"
                                               class="alliance<?php echo $planet_data['planets'][$i]->alliance_id?>">
                                                [{{ $planet_data['planets'][$i]->alliance_tag }}]
                                            </a>
                                            <a href="<?php echo $uniShortCode,'/track/alliance/',$galaxy,'/',$planet_data['planets'][$i]->alliance_id?>"
                                               class="label label-warning"><i class="icon-eye-open"></i></a>
                                            <?php }?>
                                        </td>
                                        <?php } else{ ?>
                                        <td class="gal-ply"></td>
                                        <td class="gal-rk"></td>
                                        <td class="gal-ally"></td>
                                        <?php } ?>
                                        <td><a href="<?php echo $uniShortCode,'/flight_times/',$pos,':',$i?>">
                                                <i class="icon-forward" title="<?php echo $lang->trans('ogniter.start')?>"></i></a> &nbsp;
                                            <a href="<?php echo $uniShortCode,'/flight_times/-/',$pos,':',$i?>">
                                                <i class="icon-backward" title="<?php echo $lang->trans('ogniter.destination')?>"></i></a></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="gal-pos">[<?php echo $pos?>:16]</td>
                                        <td colspan="5">(Expedition)</td>
                                        <td><a href="<?php echo $uniShortCode,'/flight_times/-/',$pos?>:16"><i class="icon-backward" title="<?php echo $lang->trans('ogniter.destination')?>"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="pull-left">
                                <a href="<?php echo $uniShortCode,'/track/free-slots/',$galaxy,'/0#system',$system?>"><?php echo $lang->trans('ogniter.occupied_planets')?>
                                    <span class="label<?php echo $class?>"><?php echo count($planet_data['planets'])?>/15</span></a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="span3">
        @include('classic.partials.servers.sidebar')
        @include('classic.partials.twitter')
    </div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function(){
            jQuery('.colorbox').colorbox({opacity :1});
        });
    </script>
@endsection