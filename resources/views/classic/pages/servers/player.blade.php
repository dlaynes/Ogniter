@extends('classic.layouts.default')

@section('title'){{ $PAGE_TITLE }}@endsection
@section('description'){{ $PAGE_DESCRIPTION }}@endsection

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
            <a href="{{ $currentPath }}">{{ $lang->trans('ogniter.og_player') .' ' . $player->name }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i>
                    {{ $currentUniverse->local_name.' - '.$lang->trans('ogniter.og_player') . ' ' . $player->name }}</h2>
            </div>
            <div class="box-content above-me">

                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active important"><a href="#tab1" data-toggle="tab"><i class="icon-user"></i> <?php echo $lang->trans('ogniter.og_player')?></a></li>
                        <?php if($show_score){ ?><li class="important"><a href="#tab2" data-toggle="tab"><i class="icon-signal"></i> <?php echo $lang->trans('ogniter.og_position').' / '.$lang->trans('ogniter.og_score')?></a></li><?php } ?>
                        <?php if(count($changes)){ ?><li class="important">
                            <a href="#tab3" data-toggle="tab"><i class="icon-zoom-in"></i> List of Changes</a>
                        </li><?php } ?>
                    </ul>
                    <div class="tab-content" id="player" style="overflow: hidden">
                        <div class="tab-pane active" id="tab1">
                            <table class="table table-striped table-condensed table-bordered table-hover">
                                <tr>
                                    <td><?php echo $lang->trans('ogniter.og_player')?>: </td>
                                    <td><strong><?php echo e($player->name)?></strong> <?php echo ($player->status)?'('.$player->string_status.')':''?>
                                        <?php
                                        if( strpos($player->string_status, 'i') ===FALSE
                                                && strpos($player->string_status, 'I')===FALSE &&
                                                strpos($player->string_status, 'a') ===FALSE && isset($rankings[7]->position )
                                        ){
                                            $honor_desc = '';

                                            $honor_position = $rankings[7]->position;
                                            $honor_score = $rankings[7]->score;
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
                                        } ?>

                                    </td>
                                </tr>
                                <?php $page = floor($player->ranking_position/100 ) + 1; ?>
                                <tr><td><?php echo $lang->trans('ogniter.og_position')?>:</td>
                                    <td><a href="<?php echo $uniShortCode.'/highscore/players/0?page='.$page.'#player'.$player->player_id?>"><?php echo $player->ranking_position?></a></td></tr>
                                <tr><td><?php echo $lang->trans('ogniter.og_score')?>:</td><td><?php echo number_format((double)$player->ranking_score)?></td></tr>
                                <tr><td><?php echo $lang->trans('ogniter.last_update')?>:</td>
                                    <td><?php echo $tagsHelper->parseTime($time -$player->last_update)?></td></tr>
                                <?php if($player->alliance_tag){ ?>
                                <tr><td><?php echo $lang->trans('ogniter.og_alliance')?>:</td>
                                    <td><a href="<?php echo $uniShortCode.'/alliance/'.$player->alliance_id?>">[<?php echo $player->alliance_tag;?>]</a></td></tr>
                                <?php } ?>
                                <?php if(isset($rankings[3]->ships)){ ?>
                                <tr><td><?php echo $lang->trans('ogniter.og_num_ships')?>:</td>
                                    <td><b class="red"><?php echo number_format((double)$rankings[3]->ships)?></b></td></tr>
                                <?php } ?>
                                <?php if(isset($rankings[0], $rankings[1], $rankings[2], $rankings[3])){

                                $defense_points = $rankings[3]->score + $rankings[2]->score + $rankings[1]->score - $rankings[0]->score;
                                if($defense_points < 0){ $defense_points = 0; }

                                $percent_defense = $rankings[0]->score ? ($defense_points/$rankings[0]->score)*100 : 0;
                                $percent_economy = $rankings[0]->score ? ($rankings[1]->score/$rankings[0]->score)*100 : 0;
                                $percent_research = $rankings[0]->score ? ($rankings[2]->score/$rankings[0]->score)*100 : 0;
                                $percent_military = $rankings[0]->score ? ($rankings[3]->score/$rankings[0]->score)*100 : 0;
                                ?>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <tr><td colspan="2">
                                        <ul class="dashboard unstyled">
                                            <li>Approximated defense points: <?php echo number_format((double)$defense_points)?></li>
                                            <li><div class="progress progress-success progress-striped">
                                                    <div class="bar" style="width: <?php echo $percent_defense?>%"><?php echo number_format((double)$percent_defense,2)?>%</div>
                                                </div></li>
                                            <li><?php echo $lang->trans('ogniter.og_economy'),': ', number_format((double)$rankings[1]->score)?></li>
                                            <li><div class="progress progress-warning progress-striped">
                                                    <div class="bar" style="width: <?php echo $percent_economy?>%"><?php echo number_format((double)$percent_economy,2)?>%</div>
                                                </div></li>
                                            <li><?php echo $lang->trans('ogniter.og_research'),': ', number_format((double)$rankings[2]->score)?></li>
                                            <li><div class="progress progress-danger progress-striped">
                                                    <div class="bar" style="width: <?php echo $percent_research?>%"><?php echo number_format((double)$percent_research,2)?>%</div>
                                                </div></li>
                                            <li><?php echo $lang->trans('ogniter.og_mil_points'),': ', number_format((double)$rankings[3]->score)?></li>
                                            <li><div class="progress progress-striped">
                                                    <div class="bar" style="width: <?php echo $percent_military?>%"><?php echo number_format((double)$percent_military,2)?>%</div>
                                                </div></li>
                                        </ul>
                                    </td></tr>

                                <?php }	?>
                                <tr>
                                    <td><i class="icon-eye-open"></i> Page Views:</td>
                                    <td><?php echo number_format((double)$player->views)?></td>
                                </tr>
                                <tr><td colspan="2">&nbsp;</td></tr>
                                <tr><td colspan="2"><h4><?php echo $lang->trans('ogniter.tools')?>:</h4></td></tr>
                                <tr><td><?php echo $lang->trans('ogniter.statistics')?></td>
                                    <td><a href="<?php echo $uniShortCode,'/statistics/1/0/month/'.$player->player_id?>" class="label label-info"><span class="icon-signal"></span> <?php echo $lang->trans('ogniter.view')?></a></td></tr>
                            </table>
                            <hr />
                            <table class="table table-striped table-bordered table-hover table-condensed">
                                <thead>
                                <tr><td colspan="6"><h4><?php echo $lang->trans('ogniter.planets')?></h4></td></tr>
                                <tr>
                                    <td>#</td>
                                    <td><?php echo $lang->trans('ogniter.og_position')?></td>
                                    <td><?php echo $lang->trans('ogniter.og_name')?></td>
                                    <td><?php echo $lang->trans('ogniter.og_type')?></td>
                                    <td><?php echo $lang->trans('ogniter.og_size')?></td>
                                    <td><?php echo $lang->trans('ogniter.last_update')?></td>
                                    <td>Changes</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach($planets as $planet){
                                if($planet->type==1){
                                    $desc = $lang->trans('ogniter.og_planet');
                                    $label = '<i class="icon-globe"></i>';
                                    $i++;
                                    $s = $i;
                                } else{
                                    $s = ' ';
                                    $label = '<i class="icon-adjust"></i>';
                                    $desc = $lang->trans('ogniter.og_moon');
                                }
                                ?>
                                <tr>
                                    <td><?php echo $s?></td>
                                    <td><a href="<?php echo $uniShortCode,'/galaxy/',$planet->galaxy,'/',$planet->system?>">[<?php echo $planet->galaxy,':',$planet->system,':',$planet->position?>]</a></td>
                                    <td><?php echo e($planet->name)?></td>
                                    <td><?php echo $label, ' (',$desc,')'?></td>
                                    <td><?php if($planet->size){ echo $planet->size.' km';} else{ echo '-'; }?></td>
                                    <td><?php echo $tagsHelper->parseTime($time -$planet->last_update)?></td>
                                    <td>
                                        <?php if($planet->conteo_cambios < 2) { ?>
                                        --
                                        <?php } else { ?>
                                        <a href="<?php echo $uniShortCode,'/ajax_planet/', $planet->planet_id?>" class="colorbox"><i class="icon icon-info"></i></a>
                                        <?php	} ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                        </div>
                        <?php if($show_score){
                        ?>
                        <div class="tab-pane" id="tab2">
                            <table class="table table-striped table-bordered table-hover table-condensed">
                                <tbody>
                                <tr><th colspan="4"><h4><span class="text-info"><?php echo $lang->trans('ogniter.og_position')?></span> / <span class="text-warning"><?php echo $lang->trans('ogniter.og_score')?></span></h4></th></tr>
                                <tr><?php for( $k =0; $k < 4; $k++){	 ?>
                                    <td><?php echo $dtypes[$k]?></td>
                                    <?php } ?></tr>
                                <tr><?php for( $k =0; $k < 4; $k++){
                                    $dtype = $dtypes[$k];
                                    ?>
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
                                            echo '<span class="text-info"><a href="',$uniShortCode,'/highscore/players/',$k,'?page=',$page,'#player',$player->player_id,'">',$rankings[$k]->position,'</a></span> / <span class="text-warning">',number_format((double)$rankings[$k]->score),'</span>'; } ?><br />
                                        <?php if($currentUniverse->api_enabled) {?><br />
                                        <?php echo $lang->trans('ogniter.og_weekly_increment'),':<br />',isset($rankings[$k]->score)?\App\Ogniter\ViewHelpers\Tags::parseDifference((double)$rankings[$k]->score-(double)$weekly_score[0]):'';?><br />
                                        <?php echo $lang->trans('ogniter.og_position'),': [ ',$weekly_dif,' ]';?><br /><br />
                                        <?php echo $lang->trans('ogniter.og_monthly_increment'),':<br />',isset($rankings[$k]->score)?\App\Ogniter\ViewHelpers\Tags::parseDifference((double)$rankings[$k]->score-(double)$monthly_score[0]):'';?><br />
                                        <?php echo $lang->trans('ogniter.og_position'),': [ ',$monthly_dif,' ]';?><br />
                                        <?php } ?>
                                    </td>
                                    <?php } ?></tr>
                                <tr><?php for( $k =4; $k < 8; $k++){	 ?>
                                    <td><?php echo $dtypes[$k]?></td>
                                    <?php } ?></tr>
                                <tr><?php for( $k =4; $k < 8; $k++){
                                    $dtype = $dtypes[$k];
                                    ?>
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
                                            echo '<span class="text-info"><a href="',$uniShortCode,'/highscore/players/',$k,'?page=',$page,'#player',$player->player_id,'">',$rankings[$k]->position,'</a></span> / <span class="text-warning">',number_format((double)$rankings[$k]->score),'</span>'; }?><br />
                                        <?php if($currentUniverse->api_enabled) {?><br />
                                        <?php echo $lang->trans('ogniter.og_weekly_increment'),':<br />',isset($rankings[$k]->score)?\App\Ogniter\ViewHelpers\Tags::parseDifference((double)$rankings[$k]->score-(double)$weekly_score[0]):'';?><br />
                                        <?php echo $lang->trans('ogniter.og_position'),': [ ',$weekly_dif,' ]';?><br /><br />
                                        <?php echo $lang->trans('ogniter.og_monthly_increment'),':<br />',isset($rankings[$k]->score)?\App\Ogniter\ViewHelpers\Tags::parseDifference((double)$rankings[$k]->score-(double)$monthly_score[0]):'';?><br />
                                        <?php echo $lang->trans('ogniter.og_position'),': [ ',$monthly_dif,' ]';?><br />
                                        <?php } ?></td>
                                    <?php } ?></tr>

                                </tbody>
                            </table>
                        </div>
                        <?php } ?>
                        <?php if(count($changes)){ ?>
                        <div class="tab-pane" id="tab3">
                            <table class="table table-striped table-bordered table-condensed table-hover text-center">
                                <thead>
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
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="fb-like" style="height: 40px" data-send="false" data-width="450" data-show-faces="false" data-action="recommend"></div>

            </div>
            <div class="box above-me">
                <div class="box-content">
                    @include('classic.partials.disqus')
                </div>
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
    <?php if($currentCountry->language =='pioneers' && $player->name=='NeoArc'){ ?>
    jQuery(document).ready(function(){
        $("#player").sparkleh({
            color: "rainbow",
            count: 40,
            overlap: 10
        });
    });
    $.fn.sparkleh = function( options ) {
        return this.each( function(k,v) {
            var $this = $(v).css("position","relative");
            var settings = $.extend({
                width: $this.outerWidth(),
                height: $this.outerHeight(),
                color: "#FFFFFF",
                count: 30,
                overlap: 0,
                speed: 1
            }, options );
            var sparkle = new Sparkle( $this, settings );
            $this.on({
                "mouseover focus" : function(e) {
                    sparkle.over();
                },
                "mouseout blur" : function(e) {
                    sparkle.out();
                }
            });
        });
    };
    function Sparkle( $parent, options ) {
        this.options = options;
        this.init( $parent );
    }
    Sparkle.prototype = {
        "init" : function( $parent ) {
            var _this = this;
            this.$canvas =
                    $("<canvas>")
                            .addClass("sparkle-canvas")
                            .css({
                                position: "absolute",
                                top: "-"+_this.options.overlap+"px",
                                left: "-"+_this.options.overlap+"px",
                                "pointer-events": "none"
                            })
                            .appendTo($parent);
            this.canvas = this.$canvas[0];
            this.context = this.canvas.getContext("2d");
            this.sprite = new Image();
            this.sprites = [0,6,13,20];
            this.sprite.src = this.datauri;
            this.canvas.width = this.options.width + ( this.options.overlap * 2);
            this.canvas.height = this.options.height + ( this.options.overlap * 2);
            this.particles = this.createSparkles( this.canvas.width , this.canvas.height );
            this.anim = null;
            this.fade = false;
        },
        "createSparkles" : function( w , h ) {
            var holder = [];
            for( var i = 0; i < this.options.count; i++ ) {
                var color = this.options.color;
                if( this.options.color == "rainbow" ) {
                    color = '#'+ ('000000' + Math.floor(Math.random()*16777215).toString(16)).slice(-6);
                } else if( $.type(this.options.color) === "array" ) {
                    color = this.options.color[ Math.floor(Math.random()*this.options.color.length) ];
                }
                holder[i] = {
                    position: {
                        x: Math.floor(Math.random()*w),
                        y: Math.floor(Math.random()*h)
                    },
                    style: this.sprites[ Math.floor(Math.random()*4) ],
                    delta: {
                        x: Math.floor(Math.random() * 1000) - 500,
                        y: Math.floor(Math.random() * 1000) - 500
                    },
                    size: parseFloat((Math.random()*2).toFixed(2)),
                    color: color
                };
            }
            return holder;
        },
        "draw" : function( time, fade ) {
            var ctx = this.context;
            ctx.clearRect( 0, 0, this.canvas.width, this.canvas.height );
            for( var i = 0; i < this.options.count; i++ ) {
                var derpicle = this.particles[i];
                var modulus = Math.floor(Math.random()*7);
                if( Math.floor(time) % modulus === 0 ) {
                    derpicle.style = this.sprites[ Math.floor(Math.random()*4) ];
                }
                ctx.save();
                ctx.globalAlpha = derpicle.opacity;
                ctx.drawImage(this.sprite, derpicle.style, 0, 7, 7, derpicle.position.x, derpicle.position.y, 7, 7);
                if( this.options.color ) {
                    ctx.globalCompositeOperation = "source-atop";
                    ctx.globalAlpha = 0.5;
                    ctx.fillStyle = derpicle.color;
                    ctx.fillRect(derpicle.position.x, derpicle.position.y, 7, 7);
                }
                ctx.restore();
            }
        },
        "update" : function() {
            var _this = this;
            this.anim = window.requestAnimationFrame( function(time) {
                for( var i = 0; i < _this.options.count; i++ ) {
                    var u = _this.particles[i];
                    var randX = ( Math.random() > Math.random()*2 );
                    var randY = ( Math.random() > Math.random()*3 );
                    if( randX ) {
                        u.position.x += ((u.delta.x * _this.options.speed) / 1500);
                    }
                    if( !randY ) {
                        u.position.y -= ((u.delta.y * _this.options.speed) / 800);
                    }
                    if( u.position.x > _this.canvas.width ) {
                        u.position.x = -7;
                    } else if ( u.position.x < -7 ) {
                        u.position.x = _this.canvas.width;
                    }
                    if( u.position.y > _this.canvas.height ) {
                        u.position.y = -7;
                        u.position.x = Math.floor(Math.random()*_this.canvas.width);
                    } else if ( u.position.y < -7 ) {
                        u.position.y = _this.canvas.height;
                        u.position.x = Math.floor(Math.random()*_this.canvas.width);
                    }
                    if( _this.fade ) {
                        u.opacity -= 0.02;
                    } else {
                        u.opacity -= 0.005;
                    }
                    if( u.opacity <= 0 ) {
                        u.opacity = ( _this.fade ) ? 0 : 1;
                    }
                }
                _this.draw( time );
                if( _this.fade ) {
                    _this.fadeCount -= 1;
                    if( _this.fadeCount < 0 ) {
                        window.cancelAnimationFrame( _this.anim );
                    } else {
                        _this.update();
                    }
                } else {
                    _this.update();
                }
            });
        },
        "cancel" : function() {
            this.fadeCount = 100;
        },
        "over" : function() {
            window.cancelAnimationFrame( this.anim );
            for( var i = 0; i < this.options.count; i++ ) {
                this.particles[i].opacity = Math.random();
            }
            this.fade = false;
            this.update();
        },
        "out" : function() {
            this.fade = true;
            this.cancel();
        },
        "datauri" : "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAHCAYAAAD5wDa1AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYxIDY0LjE0MDk0OSwgMjAxMC8xMi8wNy0xMDo1NzowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNS4xIE1hY2ludG9zaCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDozNDNFMzM5REEyMkUxMUUzOEE3NEI3Q0U1QUIzMTc4NiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDozNDNFMzM5RUEyMkUxMUUzOEE3NEI3Q0U1QUIzMTc4NiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjM0M0UzMzlCQTIyRTExRTM4QTc0QjdDRTVBQjMxNzg2IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjM0M0UzMzlDQTIyRTExRTM4QTc0QjdDRTVBQjMxNzg2Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+jzOsUQAAANhJREFUeNqsks0KhCAUhW/Sz6pFSc1AD9HL+OBFbdsVOKWLajH9EE7GFBEjOMxcUNHD8dxPBCEE/DKyLGMqraoqcd4j0ChpUmlBEGCFRBzH2dbj5JycJAn90CEpy1J2SK4apVSM4yiKonhePYwxMU2TaJrm8BpykpWmKQ3D8FbX9SOO4/tOhDEG0zRhGAZo2xaiKDLyPGeSyPM8sCxr868+WC/mvu9j13XBtm1ACME8z7AsC/R9r0fGOf+arOu6jUwS7l6tT/B+xo+aDFRo5BykHfav3/gSYAAtIdQ1IT0puAAAAABJRU5ErkJggg=="
    };
    <?php } ?>
</script>
@endsection