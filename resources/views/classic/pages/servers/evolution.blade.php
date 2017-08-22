@extends('classic.layouts.default')

@section('title'){{ $PAGE_TITLE }}@stop
@section('description'){{ $PAGE_DESCRIPTION }}@stop

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
            <a href="{{ $uniShortCode }}/evolution">Evolution</a>
        </li>
    </ul>
    @endsection

@section('scripts')
    <!--[if lte IE 8]><script type="text/javascript" src="{{ $cdnHost }}js/excanvas.min.js"></script><![endif]-->
    <script src="{{ $cdnHost }}js/jquery-ui-1.8.21.custom.min.js"></script>
    <script src="{{ $cdnHost }}js/jquery.flot.min.js"></script>
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

            var previousPoint = null;

            var $users = $("#drawing-users");
            $.plot($users, {!! json_encode($descUsers) !!},
                    {
                        xaxes: [ { mode: 'time' } ],
                        yaxes: [
                            {
                                // align if we are to the right
                                alignTicksWithAxis: 1,
                                position: 'right',
                                min:0
                            } ],
                        legend: { position: 'sw' },
                        grid: { hoverable: true }
                    });
            $users.bind("plothover", function (event, pos, item) {
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

            var $planets = $("#drawing-planets");
            $.plot($planets, {!! json_encode($descPlanets) !!},
                    {
                        xaxes: [ { mode: 'time' } ],
                        yaxes: [
                            {
                                // align if we are to the right
                                alignTicksWithAxis: 1,
                                position: 'right',
                                min:0
                            } ],
                        legend: { position: 'sw' },
                        grid: { hoverable: true }
                    });
            $planets.bind("plothover", function (event, pos, item) {
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

            var $status = $("#drawing-activity");
            $.plot($status, {!! json_encode($descStatus) !!},
                    {
                        xaxes: [ { mode: 'time' } ],
                        yaxes: [
                            {
                                // align if we are to the right
                                alignTicksWithAxis: 1,
                                position: 'right',
                                min:0
                            } ],
                        legend: { position: 'sw' },
                        grid: { hoverable: true }
                    });
            $status.bind("plothover", function (event, pos, item) {
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

@section('content')
    <div class="span12">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i>
                    <?php echo $lang->trans('ogniter.og_num_players'),' / ',$lang->trans('ogniter.og_num_alliances')?></h2>
            </div>
            <div class="box-content" id="users-container">
                <div id="drawing-users" style="width: 926px; height: 280px;"></div>
            </div>
        </div>
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i>
                    <?php echo $lang->trans('ogniter.planets'),' / ',$lang->trans('ogniter.moons')?></h2>
            </div>
            <div class="box-content" id="planets-container">
                <div id="drawing-planets" style="width: 926px; height: 280px;"></div>
            </div>
        </div>
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> <?php echo $lang->trans('ogniter.player_status')?></h2>
            </div>
            <div class="box-content" id="activity-container">
                <div id="drawing-activity" style="width: 926px; height: 280px;"></div>
            </div>
        </div>
    </div>
@endsection