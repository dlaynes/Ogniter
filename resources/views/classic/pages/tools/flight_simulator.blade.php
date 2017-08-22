@extends('classic.layouts.default')

@section('title')Ogniter - {{ $lang->trans('ogniter.flight_time_calculator') }}@stop
@section('description'){{ $lang->trans('ogniter.description_fleet_simulator_module') }}@stop
@section('head')
    <link rel="stylesheet" href="{{ $cdnHost }}js/validationEngine/css/validationEngine.jquery.css" />
@endsection
@section('breadcrumb')
<ul class="breadcrumb">
    <li>
        <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
    </li>
    <li>
        <a href="site/flight_times">{{ $lang->trans('ogniter.flight_time_calculator') }}</a>
    </li>
</ul>
@endsection
@section('content')
    <div class="span9">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right icon-white"></i> Flight Simulator</h2>
            </div>
            <div class="box-content">
                @include('classic.partials.shared.flight_form')
            </div>
        </div>
        @include('classic.partials.disqus')
    </div>
    <div class="span3">
        @include('classic.partials.shared.statistics')
        @include('classic.partials.home.countrylist')
    </div>
@endsection
@section('scripts')
    <script src="{{ $cdnHost }}js/date.js"></script>
    <script src="{{ $cdnHost }}js/validationEngine/js/languages/jquery.validationEngine-es.js"></script>
    <script src="{{ $cdnHost }}js/validationEngine/js/jquery.validationEngine.min.js"></script>
    <script src="{{ $cdnHost }}js/can.js/can.jquery-1.0.7.min.js"></script>
    <script src="{{ $cdnHost }}js/mvc/controllers/servers/FlightTimesFormCtrl.js?v=2.1"></script>
    <script>
        jQuery(document).ready(function(){
            new Ogniter.FlightTimesFormCtrl(
                    '#flight_times_form',{invalidDateTime:'<?php echo $lang->trans('ogniter.invalidDateTime')?>',mustAddAShip:'<?php echo $lang->trans('ogniter.mustAddAShip')?>'});
        });
    </script>
@endsection