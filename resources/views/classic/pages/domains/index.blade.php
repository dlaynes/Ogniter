@extends('classic.layouts.default')

@section('title'){{ $lang->trans('ogniter.title_domain_module').' '.$currentCountry->domain }}@stop
@section('description'){{ $currentCountry->domain.': '.$lang->trans('ogniter.og_servers') }}@stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/">{{ $lang->trans('ogniter.og_home') }}</a><span class="divider">/</span></li>
        <li>
            <a href="{{ $currentCountry->language }}">
                <i class="flag flag-{{ $currentCountry->flag }}"></i>
                {{ $currentCountry->domain }}</a>
        </li>
    </ul>
@endsection

@section('content')
<div class="span9">
    @include('classic.partials.domains.nav')
    <div class="box below-tab">
        <div class="box-header well">
            <h2><i class="icon-chevron-right"></i> {{ $currentCountry->language.' ('.$currentCountry->domain.')' }}</h2>
        </div>
        <div class="box-content">
            <p><span class="text-info">{{ $lang->trans('ogniter.og_choose_a_server') }}: &darr;</span></p>
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                <tr>
                    <th><?php echo $lang->trans('ogniter.og_server')?></th>
                    <th><?php echo $lang->trans('ogniter.og_acs')?></th>
                    <th><i class="icon-play" title="<?php echo $lang->trans('ogniter.og_speed')?>"></i></th>
                    <th><i class="icon-play-circle" title="<?php echo $lang->trans('ogniter.og_speed')?> (Fleet)"></i></th>
                    <th><i class="icon-refresh" title="<?php echo $lang->trans('ogniter.og_def_to_debris')?>"></i></th>
                    <th><i title="<?php echo $lang->trans('ogniter.og_debris_factor')?>">%</i></th>
                    <th><i class="icon-user" title="<?php echo $lang->trans('ogniter.og_num_players')?>"></i></th>
                    <th>Max</th>
                    <th><i class="icon-resize-horizontal" title="<?php echo $lang->trans('ogniter.universe_limits')?>"></i></th>
                    <th><?php echo $l_search?></th>
                    <th><?php echo $l_galaxy?></th>
                    <th><?php echo $l_ranking?></th>
                </tr>
                </thead>
                <tbody>
                @foreach($universeList as $universe)
                    <tr>
                        <td><a href="{{ $currentCountry->language.'/'.$universe->id.'/galaxy' }}" {!! $universe->api_enabled?'':'class="text-warning"' !!}>{{ $universe->local_name }}</a></td>
                        <td><i class="icon-{{ $universe->acs ? 'ok':'remove' }}"></i></td>
                        <td> x&nbsp;{{ (int) $universe->speed }}</td>
                        <td> x&nbsp;{{ (int) $universe->speed_fleet }}</td>
                        <td><i class="icon-{{ $universe->def_to_debris ? 'ok':'remove' }}"></i></td>
                        <td><span class="green">{{ $universe->debris_factor*100 }}%</span></td>
                        <td>{{ $universe->num_players }}</td>
                        <td>{{ number_format($universe->high_score) }}</td>
                        <td>{{ $universe->galaxies.'&nbsp;:&nbsp;'.$universe->systems }}</td>
                        <td><a href="{{ $currentCountry->language.'/'.$universe->id }}/search"
                               class="btn btn-success btn-xs" title="{{ $l_search }}"><i class="icon-search"></i></a></td>
                        <td>
                            <div class="btn-group btn-group-xs">
                                <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="icon-globe"></i> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ $currentCountry->language.'/'.$universe->id }}/galaxy/1/1" title="{{ $l_galaxy_view }}">
                                            <i class="icon-globe"></i> {{ $l_galaxy_view }}</a></li>
                                    <li><a href="{{ $currentCountry->language.'/'.$universe->id }}/track/player-status/1/i" title="{{ $l_status_search }}">
                                            <i class="icon-question-sign"></i> {{ $l_status_search }}</a></li>
                                    <li><a href="{{ $currentCountry->language.'/'.$universe->id }}/track/free-slots/1/0" title="{{ $l_colonize }}">
                                            <i class="icon-map-marker"></i> {{ $l_colonize }}</a></li>
                                    <li><a href="{{ $currentCountry->language.'/'.$universe->id }}/track/bandits-emperors/1/2" title="{{ $l_bandits }}">
                                            <i class="icon-forward"></i> {{ $l_bandits }}</a></li>
                                </ul>
                            </div>
                        </td>
                        <td><div class="btn-group btn-group-xs">
                                <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="icon-list-alt"></i> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ $currentCountry->language.'/'.$universe->id }}/highscore/players/0" title="{{ $l_players }}">
                                            <i class="icon-user"></i> {{ $l_players }}</a></li>
                                    <li><a href="{{ $currentCountry->language.'/'.$universe->id }}/highscore/alliances/0" title="{{ $l_alliances }}">
                                            <i class="icon-screenshot"></i> {{ $l_alliances }}</a></li>
                                </ul>
                            </div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="clear: both"></div>
            <div class="row-fluid">
                <div class="span6">
                    <table class="table table-striped table-condensed">
                        <tr><th colspan="6"><?php echo $lang->trans('ogniter.caption')?></th></tr>
                        <tr><td><i class="icon-play"></i></td>
                            <td><?php echo $lang->trans('ogniter.og_speed'),' (',$lang->trans('ogniter.og_server'),')'?></td>
                            <td><i class="icon-play-circle"></i></td>
                            <td><?php echo $lang->trans('ogniter.og_speed'),' (Fleet)'?></td>
                            <td>%</td>
                            <td><?php echo $lang->trans('ogniter.og_debris_factor')?></td></tr>
                        <tr><td><i class="icon-refresh"></i></td>
                            <td><?php echo $lang->trans('ogniter.og_def_to_debris')?></td><td><i class="icon-resize-horizontal"></i></td>
                            <td><?php echo $lang->trans('ogniter.universe_limits')?></td>
                            <td><i class="icon-user"></i></td>
                            <td><?php echo $lang->trans('ogniter.og_num_players')?></td></tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="box above-me">
        <div class="box-content">
            @include('classic.partials.disqus')
        </div>
    </div>
</div>
<div class="span3">
    @include('classic.partials.shared.statistics')
    @include('classic.partials.twitter')
</div>

@endsection