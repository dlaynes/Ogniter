@extends('classic.layouts.default')

@section('title')Ogniter - Collaborators @stop
@section('description')Ogniter Staff and Collaborators @stop


@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/">{{ $lang->trans('ogniter.og_home') }}</a><span class="divider">/</span></li>
        <li><a href="humans"> Staff and Collaborators</a></li>
    </ul>
@endsection

@section('content')
<div class="span9">
    <div class="box">
        <div class="box-header well">
            <h2><i class="icon-chevron-right"></i> Ogniter Staff and Collaborators</h2>
        </div>
        <div class="box-content">
            <h3 class="text-info">Ogniter Staff</h3>
            <hr />
            <h4>Programming, database design, cronjobs and maintenance tasks:</h4>
            <p>Donato Laynes (NeoArc)</p>
            <br />
            <h3 class="text-info">Collaborators</h3>
            <hr />
            <h4>Ogniter Logo:</h4>
            <p>Florent Lanternier</p>
            <h4>Ogniter Theme (base):</h4>
            <p>Charisma Admin Template (Bootstrap v2)</p>
            <h4>Translators:</h4>
            <ul>
                <li><strong>Spanish:</strong> translation by <i>NeoArc</i></li>
                <!--<li><strong>English:</strong> corrections made by [----]</li>-->
                <li><strong>German:</strong> corrections made by <i>Thath0r</i></li>
                <li><strong>French</strong> corrections made by Florent Lanternier</li>
                <li><strong>Russian:</strong> translation by Konstantin Zhuiko</li>
                <li><strong>Turkish:</strong> corrections made by Naci Batuhan</li>
                <li><strong>Romanian:</strong> translation by <i>Bagabontu</i>/<i>claudiu.radu</i></li>
                <li><strong>Other languages:</strong> Google Translate</li>
            </ul>
            <br />
            <h3 class="text-info">Thanks to:</h3>
            <hr />
            <ul>
                <li>Gameforge</li>
                <li>Antigame team</li>
                <li>Script creators</li>
                <li>The Ogame community</li>
            </ul>
        </div>
    </div>
</div>
<div class="span3">
    @include('classic.partials.shared.statistics')
    @include('classic.partials.home.countrylist')
</div>
@endsection