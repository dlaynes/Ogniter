@extends('classic.layouts.default')

@section('title'){{ $PAGE_TITLE }}@endsection
@section('description'){{ $PAGE_DESCRIPTION }}@endsection

@section('head')
    <style>
        #inner-details {
            font-size:13px;
            font-weight: bold;
        }
        .tip {
            text-align: left;
            width:auto;
            max-width:500px;
        }
        .tip-title {
            font-size: 14px;
            text-align:center;
            background-color: #eee;
            padding: 2px 4px;
        }
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
            <a href="{{ $currentPath }}">{{ $lang->trans('ogniter.og_alliance') .': '.$alliance->name.' ['.$alliance->tag.']'  }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span12">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i>
                    {{ $currentUniverse->local_name.' - '.$lang->trans('ogniter.og_alliance') .': '.$alliance->name.' ['.$alliance->tag.']' }}</h2>
            </div>
            <div class="box-content above-me">

                <?php  if($alliance->logo){
                    if(strpos($alliance->logo, 'http://')===0 || strpos($alliance->logo, 'https://')===0){
                        /*?>
                    <p>
                         <img class="img-polaroid" src="<?php echo e($alliance->logo)?>" alt="Logo" title="Logo" style="margin: auto auto" />
                     </p>
                     <hr />
                    <?php */
                    }
                }
                $page = floor($alliance->ranking_position/100 ) + 1;
                ?>
                <div class="row-fluid">
                    <div class="span6">
                        <table class="table table-striped table-condensed table-bordered table-hover">
                            <tr><td><?php echo $lang->trans('ogniter.og_alliance')?>: </td>
                                <td><strong><?php echo e($alliance->name)?> [<?php echo e($alliance->tag)?>]</strong></td></tr>
                            <tr><td><?php echo $lang->trans('ogniter.og_position')?>:</td>
                                <td><a href="<?php echo $uniShortCode,'/highscore/alliances/0?page=',$page,'#alliance',$alliance->alliance_id?>"><?php echo $alliance->ranking_position?></a></td></tr>
                            <tr><td><?php echo $lang->trans('ogniter.og_score')?>:</td><td><?php echo number_format((double)$alliance->ranking_score)?></td></tr>
                            <?php /*if($alliance->homepage){ ?>
				<tr><td class="head"><?php echo $lang->trans('ogniter.og_homepage')?>:</td><td><a href="<?php echo e($alliance->homepage)?>" target="_blank"><?php echo e($alliance->homepage)?></a></td></tr>
				<?php }*/ ?>
                            <tr><td><?php echo $lang->trans('ogniter.last_update')?>:</td>
                                <td><?php echo $tagsHelper->parseTime($time -$alliance->last_update)?></td></tr>
                            <tr><td><?php echo $lang->trans('ogniter.og_alliance_registration')?>:</td>
                                <td><strong><?php echo $alliance->open?$lang->trans('ogniter.og_open'):$lang->trans('ogniter.og_closed')?></strong></td></tr>
                            <tr><td><i class="icon-eye-open"></i> Page Views:</td>
                                <td><?php echo number_format((double)$alliance->views)?></td></tr>
                        </table>
                    </div>
                    <div class="span6">
                        <table class="table table-striped table-condensed table-bordered table-hover">
                            <tr><td colspan="2"><?php echo $lang->trans('ogniter.tools')?></td></tr>
                            <tr><td><?php echo $lang->trans('ogniter.statistics')?>:</td>
                                <td><a href="<?php echo $uniShortCode,'/statistics/2/0/month/',$alliance->alliance_id?>"
                                       class="label label-info"><span class="icon-signal icon-white"></span> <?php echo $lang->trans('ogniter.view')?></a></td></tr>
                            <tr><td><?php echo $lang->trans('ogniter.members_statistics')?>:</td>
                                <td>
                                    <a href="<?php echo $uniShortCode,'/statistics/200/0/month/',$alliance->alliance_id?>"
                                       class="label label-info"><span class="icon-signal icon-white"></span> <?php echo $lang->trans('ogniter.view')?></a>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo $lang->trans('ogniter.alliance_planets')?>:</td>
                                <td>
                                    <a href="<?php echo $uniShortCode,'/track/alliance/1/',$alliance->alliance_id?>"
                                       class="label label-warning"><span class="icon-globe icon-white"></span>
                                            <?php echo $lang->trans('ogniter.find_planets')?></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>

                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                    <tr><th colspan="8"><h4><span class="text-info"><?php echo $lang->trans('ogniter.og_position')?></span>
                                / <span class="text-warning"><?php echo $lang->trans('ogniter.og_score')?></span></h4></th></tr>
                    <tr><?php foreach($dtypes as $dtype){ ?>
                        <td><?php echo $dtype?></td>
                        <?php } ?></tr>
                    </thead>
                    <tbody>
                    <tr><?php foreach($dtypes as $k => $dtype){ ?>
                        <td><?php if(isset($rankings[$k])) {
                            $monthly_score = explode(',', $rankings[$k]->monthly_score);
                            $monthly_dif = '-';
                            if(isset($monthly_score[1])){
                            $c = $monthly_score[1] - $rankings[$k]->position;
                            if($c){
                            $monthly_dif = \App\Ogniter\ViewHelpers\Tags::parseDifference($c);
                            }
                            }

                            $weekly_score = explode(',', $rankings[$k]->weekly_score);
                            $weekly_dif = '-';
                            if(isset($weekly_score[1])){
                            $c = $weekly_score[1] - $rankings[$k]->position;
                            if($c){
                            $weekly_dif = \App\Ogniter\ViewHelpers\Tags::parseDifference($c);
                            }
                            }
                            $page = floor($rankings[$k]->position/100 ) + 1;
                            //$pos = (floor($rankings[$k]->position/100 )*100);
                            echo '<span class="text-info"><a href="',$uniShortCode,'/highscore/alliances/',$k,'?page=',$page,'#alliance',$alliance->alliance_id,'">',$rankings[$k]->position,'</a></span> / <span class="text-warning">',number_format($rankings[$k]->score),'</span>'; }?><br />
                            <?php if($currentUniverse->api_enabled){ ?>
                            <br />
                            <?php echo $lang->trans('ogniter.og_weekly_increment'),'<br />',isset($rankings[$k]->score)?\App\Ogniter\ViewHelpers\Tags::parseDifference((double)$rankings[$k]->score-(double)$weekly_score[0]):'';?><br />
                            <?php echo $lang->trans('ogniter.og_position'),': [ ',$weekly_dif,' ]';?><br /><br />
                            <?php echo $lang->trans('ogniter.og_monthly_increment'),'<br />',isset($rankings[$k]->score)?\App\Ogniter\ViewHelpers\Tags::parseDifference((double)$rankings[$k]->score-(double)$monthly_score[0]):'';?><br />
                            <?php echo $lang->trans('ogniter.og_position'),': [ ',$monthly_dif,' ]';?><br />
                            <?php } ?></td>
                        <?php } ?></tr>
                    <tr><td colspan="8">&nbsp;</td></tr>
                    <tr><td colspan="8"><h4><?php echo $lang->trans('ogniter.og_members')?>: <?php echo count($players)?></h4></td></tr>
                    <tr>
                        <td colspan="2"><?php echo $lang->trans('ogniter.og_name')?></td>
                        <td colspan="2"><?php echo $lang->trans('ogniter.og_position')?></td>
                        <td colspan="2"><?php echo $lang->trans('ogniter.og_score')?></td>
                        <td><i class="icon-eye-open"></i> Views</td>
                        <td><?php echo $lang->trans('ogniter.last_update')?></td>
                    </tr>
                    <?php

                    $normal_players = 0;
                    $inactive_players = 0;
                    $inactive_30_players = 0;
                    $vacation_players = 0;
                    $suspended_players = 0;

                    $js_players = array();

                    $count = 1;
                    $colors = ['#FFFFCC','#FCD9A1','#669999','#00FFFF','#99FFCC','#FF6600','#00FF66','#FFCCFF'];

                    foreach($players as $player){
                    $ps = (floor($player->ranking_position/100 )*100);

                    if(empty($player->status)){
                    $normal_players ++;
                    } elseif(strpos($player->string_status, 'i') !== FALSE){
                    $inactive_players++;
                    } elseif(strpos($player->string_status, 'I') !== FALSE){
                    $inactive_30_players++;
                    }
                    if(strpos($player->string_status, 'v') !== FALSE){
                    $vacation_players++;
                    }
                    if(strpos($player->string_status, 'b') !== FALSE){
                    $suspended_players++;
                    }

                    $js_players[] = '{"children": [],
						"data": {
							"description": "'.e($player->name).'",
							"$angularWidth": '. ((int) $player->ranking_score ).',
							"$color": "'.$colors[ $count % 8 ].'",
							"puntos": "'.number_format( $player->ranking_score).'"
						},
						"id": "player'.$player->player_id.'",
						"name": "'.e($player->name).'"}';
                    ?>
                    <tr>
                        <td colspan="2"><a href="<?php echo $uniShortCode,'/player/',$player->player_id?>">
                                <?php echo e($player->name). ($player->status ? '('.e($player->string_status).')':'')?></a>
                            <?php
                            if( strpos($player->status, 'i') ===FALSE
                            && strpos($player->status, 'I')===FALSE &&
                            strpos($player->status, 'a') ===FALSE
                            ){
                            $honor_desc = '';

                            $honor_position = $player->honor_position;
                            $honor_score = $player->honor_score;
                            //Is he an emperor?
                            if($honor_position < 11 && $honor_score > 14999 ){
                            $honor_desc = ' &nbsp; <i class="icon-star-empty" title="'.$lang->trans('ogniter.grand_emperor').'"></i>';
                            } elseif($honor_position < 101 && $honor_score > 2499){
                            $honor_desc = ' &nbsp; <i class="icon-star-empty" title="'.$lang->trans('ogniter.emperor').'"></i>';
                            } elseif($honor_position < 251 && $honor_score > 499){
                            $honor_desc = ' &nbsp; <i class="icon-star-empty" title="'.$lang->trans('ogniter.star_lord').'"></i>';
                            }
                            //Is he a bandit??
                            elseif($honor_position > $low_10 && $honor_score < -14999){
                            $honor_desc = ' &nbsp; <i class="icon-star" title="'.$lang->trans('ogniter.bandit_king').'"></i>';
                            }
                            elseif($honor_position > $low_100 && $honor_score < -2499){
                            $honor_desc = ' &nbsp; <i class="icon-star" title="'.$lang->trans('ogniter.bandit_lord').'"></i>';
                            }
                            elseif($honor_position > $low_250 && $honor_score < -499){
                            $honor_desc = ' &nbsp; <i class="icon-star" title="'.$lang->trans('ogniter.bandit').'"></i>';
                            }
                            echo $honor_desc;
                            }
                            ?>
                        </td>
                        <td colspan="2"><a href="<?php echo $uniShortCode,'/ranking/1/0/position/ASC/',$ps,'#player',$player->player_id?>"><?php echo $player->ranking_position?></a></td>
                        <td colspan="2"><?php echo number_format($player->ranking_score)?></td>
                        <td><?php echo $player->views?></td>
                        <td><?php echo $tagsHelper->parseTime($time -$player->last_update)?></td>
                    </tr>
                    <?php
                    $count++;
                    } ?>
                    <?php
                    $num_players = count($players);

                    if($num_players > 0){
                    $percent_normal = ($normal_players / $num_players) * 100;
                    $percent_inactive = ($inactive_players / $num_players) * 100;
                    $percent_inactive_30 = ($inactive_30_players / $num_players) * 100;
                    $percent_vacation = ($vacation_players / $num_players) * 100;
                    $percent_suspended = ($suspended_players / $num_players) * 100;
                    } else {
                    $percent_normal = 0;
                    $percent_inactive = 0;
                    $percent_inactive_30 = 0;
                    $percent_vacation = 0;
                    $percent_suspended = 0;
                    } ?>
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8"><h4><?php echo $lang->trans('ogniter.statistics'),' (',$lang->trans('ogniter.og_player'),')'?>:</h4></td>
                    </tr>
                    <tr><td colspan="4"><?php echo $lang->trans('ogniter.normal'),': ', number_format($normal_players)?></td><td colspan="4"><div class="progress progress-info progress-striped">
                                <div class="bar" style="width: <?php echo $percent_normal?>%"><?php echo number_format($percent_normal,2)?>%</div>
                            </div></td>
                    </tr>
                    <tr><td colspan="4"><?php echo $lang->trans('ogniter.og_inactive'),': ', number_format($inactive_players)?></td><td colspan="4"><div class="progress progress-success progress-striped">
                                <div class="bar" style="width: <?php echo $percent_inactive?>%"><?php echo number_format($percent_inactive,2)?>%</div>
                            </div></td></tr>
                    <tr><td colspan="4"><?php echo $lang->trans('ogniter.og_inactive_30'),': ', number_format($inactive_30_players)?></td><td colspan="4"><div class="progress progress-warning progress-striped">
                                <div class="bar" style="width: <?php echo $percent_inactive_30?>%"><?php echo number_format($percent_inactive_30,2)?>%</div>
                            </div></td></tr>
                    <tr><td colspan="4"><?php echo $lang->trans('ogniter.og_v_mode'),': ', number_format($vacation_players)?></td><td colspan="4"><div class="progress progress-danger progress-striped">
                                <div class="bar" style="width: <?php echo $percent_vacation?>%"><?php echo number_format($percent_vacation,2)?>%</div>
                            </div></td></tr>
                    <tr><td colspan="4"><?php echo $lang->trans('ogniter.og_suspended'),': ', number_format($suspended_players)?></td><td colspan="4"><div class="progress progress-striped">
                                <div class="bar" style="width: <?php echo $percent_suspended?>%"><?php echo number_format($percent_suspended,2)?>%</div>
                            </div></td></tr>
                    </tbody>
                </table>

                <?php if(count($changes)){ ?>

                <table class="table table-striped table-bordered table-condensed table-hover text-center">
                    <thead>
                    <tr><th colspan="4"><h4><span class="text-info">Alliance Changes</span></h4></td></tr>
                    <tr>
                        <th>Change</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($changes as $change){ ?>
                    <tr class="text-left">
                        <td><strong><?php echo $change['change']?></strong></td>
                        <td><?php echo $change['from']?></td>
                        <td><?php echo $change['to']?></td>
                        <td><?php echo $change['date']?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php } ?>

                <div class="clearfix"></div>
                <div id="visualize" style="width: 700px; height: 700px; margin: 0 auto">

                </div>
                <div class="clearfix"></div>

            </div>
        </div>
        <div class="box above-me">
            <div class="box-content">
                @include('classic.partials.disqus')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ $cdnHost }}js/jit-yc.js"></script>
    <script>
        var labelType, useGradients, nativeTextSupport, animate;
        (function() {
            var ua = navigator.userAgent,
                    iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
                    typeOfCanvas = typeof HTMLCanvasElement,
                    nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
                    textSupport = nativeCanvasSupport
                            && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
            //I'm setting this based on the fact that ExCanvas provides text support for IE
            //and that as of today iPhone/iPad current text support is lame
            labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
            nativeTextSupport = labelType == 'Native';
            useGradients = nativeCanvasSupport;
            animate = !(iStuff || !nativeCanvasSupport);
        })();

        var json = {
            "children": [
                <?php echo implode(',', $js_players)?>
            ],
            "data": {
                "$type": "none"
            },
            "id": "alliance",
            "name": "<?php echo e($alliance->name)?> \n [<?php echo e($alliance->tag)?>]"
        };

        jQuery(document).ready(function(){
            var sb = new $jit.Sunburst({
                //id container for the visualization
                injectInto: 'visualize',
                //Distance between levels
                levelDistance: 160,
                //Change node and edge styles such as
                //color, width and dimensions.
                Node: {
                    overridable: true,
                    type: useGradients? 'gradient-multipie' : 'multipie'
                },
                //Select canvas labels
                //'HTML', 'SVG' and 'Native' are possible options
                Label: {
                    type: labelType
                },
                //Change styles when hovering and clicking nodes
                NodeStyles: {
                    enable: true,
                    type: 'Native',
                    stylesClick: {
                        'color': '#33dddd'
                    },
                    stylesHover: {
                        'color': '#dd3333'
                    }
                },
                //Add tooltips
                Tips: {
                    enable: true,
                    onShow: function(tip, node) {
                        var html = "<div class=\"tip-title\">" + node.name +'<br />';
                        var data = node.data;
                        if("puntos" in data) {
                            html += "<b><?php echo $lang->trans('ogniter.og_score')?>:</b> " + data.puntos;
                        }
                        tip.innerHTML = html + "</div>";
                    }
                },
                //implement event handlers
                Events: {
                    enable: true,
                    onClick: function(node) {
                        if(!node) return;

                        /*
                         //Build detailed information about the file/folder
                         //and place it in the right column.
                         var html = "<h4>" + node.name + "</h4>", data = node.data;
                         if("days" in data) {
                         html += "<b>Last modified:</b> " + data.days + " days ago";
                         }
                         if("size" in data) {
                         html += "<br /><br /><b>File size:</b> " + Math.round(data.size / 1024) + "KB";
                         }
                         if("description" in data) {
                         html += "<br /><br /><b>Last commit was:</b><br /><pre>" + data.description + "</pre>";
                         }
                         $jit.id('inner-details').innerHTML = html;
                         */

                        //hide tip
                        sb.tips.hide();
                        //rotate
                        sb.rotate(node, animate? 'animate' : 'replot', {
                            duration: 1000,
                            transition: $jit.Trans.Quart.easeInOut
                        });
                    }
                },
                // Only used when Label type is 'HTML' or 'SVG'
                // Add text to the labels.
                // This method is only triggered on label creation
                onCreateLabel: function(domElement, node){
                    var labels = sb.config.Label.type,
                            aw = node.getData('angularWidth');
                    if (labels === 'HTML' && (node._depth < 2 || aw > 2000)) {
                        domElement.innerHTML = node.name;
                    } else if (labels === 'SVG' && (node._depth < 2 || aw > 2000)) {
                        domElement.firstChild.appendChild(document.createTextNode(node.name));
                    }
                },
                // Only used when Label type is 'HTML' or 'SVG'
                // Change node styles when labels are placed
                // or moved.
                onPlaceLabel: function(domElement, node){
                    var labels = sb.config.Label.type;
                    if (labels === 'SVG') {
                        var fch = domElement.firstChild;
                        var style = fch.style;
                        style.display = '';
                        style.cursor = 'pointer';
                        style.fontSize = "1em";
                        fch.setAttribute('fill', "#fff");
                    } else if (labels === 'HTML') {
                        var style = domElement.style;
                        style.display = '';
                        style.cursor = 'pointer';
                        style.fontSize = "1em";
                        style.color = "#eee";
                        var left = parseInt(style.left);
                        var w = domElement.offsetWidth;
                        style.left = (left - w / 2) + 'px';
                    }
                }
            });
            //load JSON data.
            sb.loadJSON(json);
            //compute positions and plot.
            sb.refresh();
        });
        //end
    </script>
@endsection