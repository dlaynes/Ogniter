@extends('classic.layouts.default')

@section('title')Ogniter - Poll # {{ $poll->id }}@stop
@section('description')Ogniter Poll - {{ $poll->question }}@stop

@section('scripts')
    <!--[if lte IE 8]><script src="{{ $cdnHost }}js/excanvas.min.js"></script><![endif]-->
    <script src="{{ $cdnHost }}js/jquery.flot.min.js"></script>
    <script src="{{ $cdnHost }}js/jquery.flot.pie.min.js"></script>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="site/polls">Polls</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="site/poll/<?php echo $poll->id ?>"><?php echo $lang->trans('ogniter.poll_results')?></a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> <?php echo $lang->trans('ogniter.poll_results')?></h2>
            </div>
            <div class="box-content">
                <h5><?php echo $poll->question?></h5>
                <?php
                $js_answers = array();

                $total_votes = 0;

                $count = 1;
                $colors = ['#FFFFCC','#FCD9A1','#669999','#00FFFF','#99FFCC','#FF6600','#00FF66','#FFCCFF'];
                foreach($poll->answers as $answer){
                    $js_answers[] = '{
							"label": "'.htmlspecialchars($answer->answer).'",
							"data": '. $answer->votes.',
							"color": "'.$colors[ $count % 8 ].'"
						}';
                    $total_votes += $answer->votes;
                    $count++;
                }?>
                <div class="clearfix"></div>
                <div id="visualize" style="width: 700px; height: 496px; margin: 0 auto"></div>
                <p>Total votes: <?php echo $total_votes?></p>
                <div class="clearfix"></div>
                <script>
                    jQuery(document).ready(function(){
                        $.plot($("#visualize"), [
                            <?php echo implode(',', $js_answers)?>
                        ], {
                            series: {
                                pie: {
                                    radius: 0.7,
                                    tilt: 0.8,
                                    label: {
                                        show: true,
                                        radius: 0.6,
                                        formatter: function(label, series){
                                            var str = series.data;
                                            return '<div style="font-size:10px;text-align:center;padding:2px 4px;color:#000;">'+label+'<br/>'+Math.round(series.percent)+'% ( Votes: '+String(str).substring(2, str.lenght)+' )</div>';
                                        },
                                        background: { opacity: 0.8 }
                                    },
                                    show: true
                                }
                            },
                            legend: {
                                show: false
                            }
                        });
                    });
                </script>
                <p><a href="javascript: history.back()" class="btn btn-primary">&larr; Back</a></p>
            </div>
        </div>
        @include('classic.partials.disqus')
    </div>

    <div class="span3">
        @include('classic.partials.shared.statistics')
        @include('classic.partials.twitter')
    </div>
@endsection