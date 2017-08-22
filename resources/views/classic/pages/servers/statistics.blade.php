@extends('classic.layouts.default')

@section('title'){{ $PAGE_TITLE }}@endsection
@section('description'){{ $PAGE_DESCRIPTION }}@endsection

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
            <a href="{{ $currentPath }}">{{ ' ' }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span12">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i>
                    {{ $currentUniverse->local_name.' - ' }}</h2>
            </div>
            <div class="box-content above-me">

                <?php
                $stats_url = $uniShortCode.'/statistics/';;
                ?><div class="servers-content">
                    {!! Form::open(['url'=>$stats_url.$category.'/'.$type.'/all/'.$from_ids]) !!}
                        <div class="btn-toolbar">

                            <div class="btn-group">
                                <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="javascript:;"> <?php echo $period_name?><span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $stats_url,$category,'/',$type,'/week/',$from_ids?>"><?php echo $lang->trans('ogniter.by_week')?></a></li>
                                    <li><a href="<?php echo $stats_url,$category,'/',$type,'/month/',$from_ids?>"><?php echo $lang->trans('ogniter.by_month')?></a></li>
                                    <li><a href="<?php echo $stats_url,$category,'/',$type,'/year/',$from_ids?>"><?php echo $lang->trans('ogniter.by_year')?></a></li>
                                    <li><a href="<?php echo $stats_url,$category,'/',$type,'/all/',$from_ids?>"><?php echo $lang->trans('ogniter.all')?></a></li>
                                </ul>
                            </div>

                            <div class="btn-group"><a class="btn<?php if($type==0){ echo ' btn-info';}?>" href="<?php echo $stats_url,$category,'/0/',$period,'/',$from_ids?>"><?php echo $dtypes[0]?></a></div>
                            <div class="btn-group"><a class="btn<?php if($type==1){ echo ' btn-info';}?>" href="<?php echo $stats_url,$category,'/1/',$period,'/',$from_ids?>"><?php echo $dtypes[1]?></a></div>
                            <div class="btn-group"><a class="btn<?php if($type==2){ echo ' btn-info';}?>" href="<?php echo $stats_url,$category,'/2/',$period,'/',$from_ids?>"><?php echo $dtypes[2]?></a></div>
                            <div class="btn-group"><?php $text = ($type > 2 && $type < 7)? $dtypes[$type] : $dtypes[3]; ?>
                                <a class="btn <?php if($type > 2 && $type < 7){ echo ' btn-info'; }?> dropdown-toggle" data-toggle="dropdown" href="javascript:;"><?php echo $text?> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $stats_url,$category,'/3/',$period,'/',$from_ids?>"><?php echo $dtypes[3]?></a></li>
                                    <li><a href="<?php echo $stats_url,$category,'/4/',$period,'/',$from_ids?>"><?php echo $dtypes[4]?></a></li>
                                    <li><a href="<?php echo $stats_url,$category,'/5/',$period,'/',$from_ids?>"><?php echo $dtypes[5]?></a></li>
                                    <li><a href="<?php echo $stats_url,$category,'/6/',$period,'/',$from_ids?>"><?php echo $dtypes[6]?></a></li>
                                </ul>
                            </div>
                            <div class="btn-group"><a class="btn<?php if($type==7){ echo ' btn-info';}?>" href="<?php echo $stats_url,$category,'/7/',$period,'/',$from_ids?>"><?php echo $dtypes[7]?></a></div>
                        </div>
                        <div class="clear"></div>
                        <div class="btn-toolbar">
                            <div class="btn-group">
                                <span>From Date: &nbsp;</span>
                                <input type="text" name="limit" id="limit" value="<?php echo !is_null($limit) ? date('Y-m-d', $limit): '';?>" />
                            </div>
                            <div class="btn-group">
                                <span>To Date:  &nbsp;</span>
                                <input type="text" name="until" id="until" value="<?php echo !is_null($until) ? date('Y-m-d', $until): '';?>" />
                            </div>
                            <div class="btn-group">
                                <input type="submit" class="btn btn-primary" value="<?php echo $lang->trans('ogniter.og_send')?>" style="position: relative; top: 6px" />
                            </div>
                        </div>

                    {!! Form::close() !!}
                    <hr />
                    <?php
                    $rows = '';
                    $rows_pos = '';
                    $table_rows = '';
                    $position_rows = '';

                    foreach($statistics as $id => $r){
                        $data = $r['data'];
                        $data_str = '';
                        $data_pos = '';

                        $min_by_user = 0;
                        $min_by_user_position = 0;
                        $max_by_user = 0;
                        $max_by_user_position = 0;
                        $min_last_update = 0;
                        $max_last_update = 0;

                        foreach($data as $rw){
                            if($min_last_update){
                                if($rw->last_update <  $min_last_update){
                                    $min_by_user =  $rw->score;
                                    $min_by_user_position =  $rw->position;
                                    $min_last_update = $rw->last_update;
                                }
                            } else{
                                $min_by_user =  $rw->score;
                                $min_by_user_position =  $rw->position;
                                $min_last_update = $rw->last_update;
                            }
                            if($max_last_update){
                                if($rw->last_update >  $max_last_update){
                                    $max_by_user =  $rw->score;
                                    $max_by_user_position =  $rw->position;
                                    $max_last_update = $rw->last_update;
                                }
                            } else{
                                $max_by_user =  $rw->score;
                                $min_by_user_position =  $rw->position;
                                $max_last_update = $rw->last_update;
                            }
                            $data_str .= '['.($rw->last_update*1000).','.$rw->score.'],';
                            $data_pos .= '['.($rw->last_update*1000).','.($rw->position).'],';
                        }
                        if($data_str){
                            $data_str = '['.substr($data_str, 0, strlen($data_str)-1).']';
                        } else{
                            $data_str = '[]';
                        }
                        if($data_pos){
                            $data_pos = '['.substr($data_pos, 0, strlen($data_pos)-1).']';
                        } else{
                            $data_pos = '[]';
                        }

                        $table_rows .= '<tr><th><a href="'.$uniShortCode.'/'.$cat_name.'/'.$id.'">'.e($r['row']->name).'</a></th>
			<td>'.number_format($min_by_user).'&nbsp;('.date('Y-m-d', $min_last_update ).')</td><td>'.number_format($max_by_user).'&nbsp;('.date('Y-m-d', $max_last_update ).')</td>
			<td>'.$tagsHelper->parseDifference($max_by_user-$min_by_user).'</td></tr>';
                        $position_rows .= '<tr><th><a href="'.$uniShortCode.'/'.$cat_name.'/'.$id.'">'.e($r['row']->name).'</a></th>
			<td>'.number_format($min_by_user_position).'&nbsp;('.date('Y-m-d', $min_last_update ).')</td><td>'.number_format($max_by_user_position).'&nbsp;('.date('Y-m-d', $max_last_update ).')</td>
			<td>'.$tagsHelper->parseDifference(-($max_by_user_position-$min_by_user_position)).'</td></tr>';
                        $rows .= '{data:'.$data_str.', label: "'.e($r['row']->name).'", lines: { show: true }, points: { show: true }},';
                        $rows_pos .= '{data:'.$data_pos.', label: "'.e($r['row']->name).'", lines: { show: true }, points: { show: true }},';

                    }
                    if($rows!=''){
                        $rows = substr($rows,0, strlen($rows)-1);
                    }
                    if($rows_pos!=''){
                        $rows_pos = substr($rows_pos,0, strlen($rows_pos)-1);
                    }
                    $height = 500 + count($statistics)*10;
                    ?>
                    <table class="table table-striped table-bordered table-condensed table-hover" style="width: 250px">
                        <thead>
                            <tr><th><?php echo $lang->trans('ogniter.og_name')?></th><th>Start points</th><th>End points</th><th>Diff</th></tr>
                        </thead>
                        <tbody>
                        <?php echo $table_rows?>
                        </tbody>
                    </table>
                    <hr />

                    <div id="drawing-board" style="width: 926px; height: <?php echo $height?>px;"></div>

                    <hr />
                    <h4><?php echo $lang->trans('ogniter.og_position')?></h4><br />
                    <table class="table table-striped table-bordered table-condensed table-hover" style="width: 250px">
                        <thead>
                        <tr><th><?php echo $lang->trans('ogniter.og_name')?></th><th>Start</th><th>End</th><th>Diff</th></tr>
                        </thead>
                        <tbody>
                        <?php echo $position_rows?>
                        </tbody>
                    </table>
                    <div id="drawing-board-2" style="width: 926px; height: 400px"></div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('head')
    <style>
        /*!
         * Pikaday
         * Copyright © 2014 David Bushell | BSD & MIT license | http://dbushell.com/
         */

        .pika-single {
            z-index: 9999;
            display: block;
            position: relative;
            color: #333;
            background: #fff;
            /* border: 1px solid #ccc; */
            border-bottom-color: #bbb;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        /*
        clear child float (pika-lendar), using the famous micro clearfix hack
        http://nicolasgallagher.com/micro-clearfix-hack/
        */
        .pika-single:before,
        .pika-single:after {
            content: " ";
            display: table;
        }
        .pika-single:after { clear: both }
        .pika-single { *zoom: 1 }

        .pika-single.is-hidden {
            display: none;
        }

        .pika-single.is-bound {
            position: absolute;
            box-shadow: 0 5px 15px -5px rgba(0,0,0,.5);
        }

        .pika-lendar {
            float: left;
            width: 225px;
            font-size: 10px;
            position: relative;
        }

        .calendar .pika-lendar{
            width: 165px;
        }

        .pika-title {
            position: relative;
            text-align: center;
            background: #1e5799; /* Old browsers */
            background: -moz-linear-gradient(top, #1e5799 0%, #2989d8 50%, #086ed3 100%, #7db9e8 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#1e5799), color-stop(50%,#2989d8), color-stop(100%,#086ed3), color-stop(100%,#7db9e8)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top, #1e5799 0%,#2989d8 50%,#086ed3 100%,#7db9e8 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top, #1e5799 0%,#2989d8 50%,#086ed3 100%,#7db9e8 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top, #1e5799 0%,#2989d8 50%,#086ed3 100%,#7db9e8 100%); /* IE10+ */
            background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#086ed3 100%,#7db9e8 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#7db9e8',GradientType=0 ); /* IE6-9 */
        }
        .pika-label {
            display: inline-block;
            *display: inline;
            position: relative;
            z-index: 9999;
            overflow: hidden;
            margin: 0;
            padding: 5px 3px;
            font-size: 12px;
            line-height: 10px;
            color: #fff;
        }
        .pika-title select {
            cursor: pointer;
            position: absolute;
            z-index: 9998;
            margin: 0;
            left: 0;
            top: 0;
            filter: alpha(opacity=0);
            opacity: 0;
            height: 21px;
            line-height: 15px;
            width: auto;
        }

        .pika-prev,
        .pika-next {
            display: block;
            cursor: pointer;
            position: relative;
            outline: none;
            border: 0;
            padding: 0;
            width: 20px;
            height: 21px;
            /* hide text using text-indent trick, using width value (it's enough) */
            text-indent: 20px;
            white-space: nowrap;
            overflow: hidden;
            background-color: transparent;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: 75% 75%;
            *position: absolute;
            *top: 0;
        }

        .pika-prev:hover,
        .pika-next:hover {
            opacity: 1;
        }

        .pika-prev,
        .is-rtl .pika-next:after {
            float: left;
            background-image: url('../images/tab_left.png');
            background-position: center center;
            *left: 0;
        }

        .pika-next,
        .is-rtl .pika-prev:after {
            float: right;
            background-image: url('../images/tab_right.png');
            background-position: center center;
            *right: 0;
        }

        .pika-prev.is-disabled,
        .pika-next.is-disabled {
            cursor: default;
            opacity: .2;
        }

        .pika-select {
            display: inline-block;
            *display: inline;
        }

        .pika-table {
            position: absolute; top: 24px;
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: 0;
            background-color: #fff;
        }

        .pika-table th,
        .pika-table td {
            font-size: 8px;
            width: 14%;
            padding: 0;
            border: 1px solid #ccc;
            text-align: center;
            font-weight: bold;
        }

        .pika-table th {
            color: #000;
            font-size: 8px;
            line-height: 15px;
            border: 0 none;
        }
        .pika-table td button { text-align: center; font-weight: bold; }

        .pika-button {
            cursor: pointer;
            display: block;
            outline: none;
            border: 0;
            margin: 0;
            width: 100%;
            color: #666;
            font-size: 9px;
            line-height: 15px;
            text-align: right;
            background: #f5f5f5;
        }
        .pika-table abbr { border: 0 none;}

        .pika-week {
            font-size: 10px;
            color: #999;
        }

        .is-today .pika-button {
            color: #33aaff;
            font-weight: bold;
        }

        .is-selected .pika-button {
            color: #fff;
            font-weight: bold;
            background: #33aaff;
            box-shadow: inset 0 1px 3px #178fe5;
            border-radius: 3px;
        }

        .is-disabled .pika-button {
            pointer-events: none;
            cursor: default;
            color: #999;
            opacity: .3;
        }

        .pika-button:hover {
            color: #fff !important;
            background: #ff8000 !important;
            box-shadow: none !important;
            border-radius: 3px !important;
        }
    </style>
@endsection

@section('scripts')
    <!--[if lte IE 8]><script type="text/javascript" src="{{ $cdnHost }}js/excanvas.min.js"></script><![endif]-->
    <script src="{{ $cdnHost }}js/jquery.flot.min.js"></script>
    <script src="{{ $cdnHost }}js/moment.min.js"></script>
    <script src="{{ $cdnHost }}js/pikaday.js"></script>
    <script>
        function addCommas(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css( {
                top: y,
                left: x + 5
            }).appendTo("body").fadeIn(200).animate({top:y+5}, 100);
        }

        jQuery(document).ready(function(){
            var pickers = [
                new Pikaday({ field: document.getElementById('limit'), format: 'YYYY-MM-DD' }),
                new Pikaday({ field: document.getElementById('until'), format: 'YYYY-MM-DD' })
            ];

            $.plot($("#drawing-board"),
                    [ <?php echo $rows?>],
                    {
                        xaxes: [ { mode: 'time' } ],
                        yaxes: [
                            {
                                // align if we are to the right
                                alignTicksWithAxis: 1,
                                position: 'right'
                            } ],
                        legend: { position: 'sw' },
                        grid: { hoverable: true }
                    });

            var previousPoint = null;
            $("#drawing-board").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));

                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;
                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                        showTooltip(item.pageX, item.pageY,
                                //x = timestamp!!
                                item.series.label + ": " + addCommas(parseInt(y,10 ) ) );
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }

            });
            $.plot($("#drawing-board-2"),
                    [ <?php echo $rows_pos?>],
                    {
                        xaxes: [ { mode: 'time' } ],
                        yaxes: [
                            {
                                // align if we are to the right
                                alignTicksWithAxis: 1,
                                position: 'right',
                                transform: function (v) { return -v; },
                                inverseTransform: function (v) { return -v; }
                            } ],
                        legend: { position: 'sw' },
                        grid: { hoverable: true }
                    });

            var previousPoint = null;
            $("#drawing-board-2").bind("plothover", function (event, pos, item) {
                $("#x").text(pos.x.toFixed(2));
                $("#y").text(pos.y.toFixed(2));

                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;
                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(2),
                                y = item.datapoint[1].toFixed(2);
                        showTooltip(item.pageX, item.pageY,
                                //x = timestamp!!
                                item.series.label + ": " + addCommas(parseInt(y,10 ) ) );
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }

            });
        });
    </script>
@endsection