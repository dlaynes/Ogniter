<?php
$a = array();
$a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_index');
$a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_index');

$a = str_replace(array('%server%','%domain%'), array($currentUniverse->local_name, $currentCountry->domain), $a);
?>
@extends('classic.layouts.default')
@section('title'){{ $a['PAGE_TITLE'] }}@endsection
@section('description'){{ $a['PAGE_DESCRIPTION'] }}@endsection

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
            <a href="{{ $uniShortCode }}">{{ $currentUniverse->local_name }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $currentUniverse->local_name .' ('.$currentCountry->domain.')' }}</h2>
            </div>
            <div class="box-content above-me">
                <section>
                    <div>
                        <table class="table table-condensed">
                            <tr><th>{{$lang->trans('ogniter.og_name')}}:</th>
                                <td>{{$currentUniverse->local_name}}</td>
                                <th>{{$lang->trans('ogniter.og_language')}}:</th>
                                <td>{{$currentUniverse->language}}</td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_timezone')}}:</th>
                                <td>{{$currentUniverse->timezone}}</td>
                                <th>{{$lang->trans('ogniter.og_ogame_version')}}:</th>
                                <td>{{$currentUniverse->version}}</td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_speed')}}:</th>
                                <td><span class="blue"> x {{ intval($currentUniverse->speed) }}</span></td>
                                <th>{{$lang->trans('ogniter.og_speed')}} (Fleet):</th>
                                <td><span class="blue"> x {{ intval($currentUniverse->speed_fleet) }}</span></td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><th>Donut Galaxy?:</th><td>
                                    <?php
                                    echo ($currentUniverse->donut_galaxy)?$lang->trans('ogniter.og_enabled'):$lang->trans('ogniter.og_disabled');
                                    ?></td>
                                <th>Donut Systems?:</th><td><?php
                                    echo ($currentUniverse->donut_system)?$lang->trans('ogniter.og_enabled'):$lang->trans('ogniter.og_disabled');
                                    ?>
                                </td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_acs')}}:</th>
                                <td>{{($currentUniverse->acs)?$lang->trans('ogniter.og_enabled'):$lang->trans('ogniter.og_disabled') }}</td>
                                <th>Extra fields:</th>
                                <td><span class="green">+{{$currentUniverse->extra_fields}}</span></td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_galaxies')}}:</th>
                                <td>{{$currentUniverse->galaxies}}</td>
                                <th>{{$lang->trans('ogniter.og_systems')}}:</th>
                                <td>{{$currentUniverse->systems}}</td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>

                            <tr><th>{{$lang->trans('ogniter.og_rapid_fire')}}:</th>
                                <td>{!! ($currentUniverse->rapidfire)?
                                    '<i class="icon-ok"></i>':'<i class="icon-remove"></i>' !!}</td>
                                <th>{{$lang->trans('ogniter.og_def_to_debris')}}:</th>
                                <td>{!! ($currentUniverse->def_to_debris)?
                                    '<i class="icon-ok"></i>':'<i class="icon-remove"></i>' !!}</td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_debris_factor')}}:</th>
                                <td>{{$currentUniverse->debris_factor*100}}%</td>
                                <th>{{$lang->trans('ogniter.og_repair_factor')}}:</th>
                                <td>{{$currentUniverse->repair_factor*100}}%</td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_newbie_protection_limit')}}:</th>
                                <td>
                                    {{ sprintf($lang->trans('ogniter.og_points_limit'), $currentUniverse->newbie_protection_limit) }}</td>
                                <th>{{$lang->trans('ogniter.og_newbie_protection_high')}}:</th>
                                <td>
                                    {{sprintf($lang->trans('ogniter.og_points_limit'), $currentUniverse->newbie_protection_high)}}</td></tr>
                            <?php if( !empty($currentUniverse->wf_enabled) ) { ?>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><th>Wreckfields?</th><td><?php echo $lang->trans('ogniter.og_enabled')?></td><th>Minimum resource lost</th><td><?php echo number_format($currentUniverse->wf_minimun_res_lost)?></td></tr>
                            <tr><th>Minimum Loss percentage</th><td><?php echo $currentUniverse->wf_minimun_loss_perc?>%</td><th>Basic percentage repair</th><td><?php echo $currentUniverse->wf_basic_percentage_repair?>%</td></tr>
                            <?php } else { ?>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><th>Wreckfields?</th><td><?php echo $lang->trans('ogniter.og_disabled');?></td><td colspan="2"></td></tr>
                            <?php } ?>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td colspan="4"><h4>{{$lang->trans('ogniter.og_other_info')}}</h4></td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_num_players')}}:</th>
                                <td><span class="green">{{number_format($universeStatistics->num_players)}}</span></td>
                                <th>{{$lang->trans('ogniter.og_max_score')}}:</th>
                                <td>{{number_format($currentUniverse->highscore)}}</td></tr>
                            <tr><th>{{$lang->trans('ogniter.planets')}}:</th>
                                <td><span class="blue">{{number_format($universeStatistics->num_planets)}}</span></td>
                                <th>{{$lang->trans('ogniter.moons')}}:</th>
                                <td><span class="yellow">{{number_format($universeStatistics->num_moons)}}</span></td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_num_alliances')}}:</th>
                                <td><span class="red">{{number_format($universeStatistics->num_alliances)}}</span></td>
                                <th></th><td></td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr>
                                <td colspan="4"><h4>{{$lang->trans('ogniter.statistics').' ('.$lang->trans('ogniter.og_player').')'}}</h4></td>
                            </tr><tr><th>{{$lang->trans('ogniter.normal').': '. number_format($universeStatistics->normal_players)}}</th>
                                <td colspan="3">
                                    <div class="progress progress-info progress-striped">
                                        <div class="bar" style="width: <?php echo $percent_normal?>%"><?php echo number_format($percent_normal,2)?>%</div>
                                </td>
                            </tr>
                            <tr><th>{{$lang->trans('ogniter.og_outlaw').': '. number_format($universeStatistics->outlaw_players)}}</th>
                                <td colspan="3"><div class="progress progress-striped">
                                        <div class="bar" style="width: <?php echo $percent_outlaw?>%"><?php echo number_format($percent_outlaw,2)?>%</div>
                                    </div></td>
                            </tr><tr><th>{{$lang->trans('ogniter.og_inactive').': '. number_format($universeStatistics->inactive_players)}}</th>
                                <td colspan="3">
                                    <div class="progress progress-success progress-striped">
                                        <div class="bar" style="width: <?php echo $percent_inactive?>%"><?php echo number_format($percent_inactive,2)?>%</div></td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_inactive_30').': '. number_format($universeStatistics->inactive_30_players)}}</th>
                                <td colspan="3">
                                    <div class="progress progress-warning progress-striped">
                                        <div class="bar" style="width: <?php echo $percent_inactive_30?>%"><?php echo number_format($percent_inactive_30,2)?>%</div>
                                    </div></td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_v_mode').': '. number_format($universeStatistics->vacation_players)}}</th>
                                <td colspan="3"><div class="progress progress-danger progress-striped">
                                        <div class="bar" style="width: <?php echo $percent_vacation?>%"><?php echo number_format($percent_vacation,2)?>%</div>
                                    </div></td></tr>
                            <tr><th>{{$lang->trans('ogniter.og_suspended').': '. number_format($universeStatistics->suspended_players)}}</th><td colspan="3">
                                    <div class="progress progress-striped">
                                        <div class="bar" style="width: <?php echo $percent_suspended?>%"><?php echo number_format($percent_suspended,2)?>%</div>
                                    </div></td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td> <a href="{{$uniShortCode}}/search-form" class="btn btn-mini btn-success" title="{{$lang->trans('ogniter.og_search')}}"><i class="icon-search"></i> {{$lang->trans('ogniter.og_search')}}</a> </td>
                                <td> <a href="{{$uniShortCode}}/galaxy" class="btn btn-mini btn-danger" title="{{$lang->trans('ogniter.og_galaxy_view')}}"><i class="icon-globe"></i> {{$lang->trans('ogniter.og_galaxy_view')}}</a></td>
                                <td> <a href="{{$uniShortCode}}/highscore/players/0" class="btn btn-mini btn-primary" title="{{$lang->trans('ogniter.og_ranking')}}"><i class="icon-list-alt"></i> {{$lang->trans('ogniter.og_ranking')}}</a></td>
                            </tr>
                        </table>
                    </div>
                </section>
            </div>
        </div>
        <div class="box above-me">
            <div class="box-content">
                @include('classic.partials.disqus')
            </div>
        </div>
    </div>
    <div class="span3">
        @include('classic.partials.servers.sidebar')
        @include('classic.partials.twitter')
    </div>
@endsection