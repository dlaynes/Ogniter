<ul class="nav nav-tabs" style="border-bottom: 0 none">
    <li @if($is_search) class="active" @endif>
        <a href="{{ $uniShortCode }}/search-form"
           @if(!$is_search) aria-expanded="false" @else aria-expanded="true" @endif>
            <i class="icon-search"></i> {{ $lang->trans('ogniter.og_search') }}</a>
    </li>
    <li class="dropdown @if($is_ranking) active @endif">
        <a class="dropdown-toggle" data-toggle="dropdown"  href="javascript:;"
           @if(!$is_ranking) aria-expanded="false" @else aria-expanded="true" @endif>
            <i class="{{ $top_icon_ranking }}"></i> {{ $top_title_ranking }} <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="{{ $uniShortCode }}/highscore/players/0" title="<?php echo $lang->trans('ogniter.players')?>"><i class="icon-user"></i> <?php echo $lang->trans('ogniter.og_player')?></a></li>
            <li><a href="{{ $uniShortCode }}/highscore/alliances/0" title="<?php echo $lang->trans('ogniter.alliances')?>"><i class="icon-screenshot"></i> <?php echo $lang->trans('ogniter.og_alliance')?></a></li>
            <li><a href="{{ $uniShortCode }}/top_flop"><i class="icon-retweet"></i> <?php echo $lang->trans('ogniter.top').' / '.$lang->trans('ogniter.flop')?></a></li>
        </ul>
    </li>
    <li class="dropdown @if($is_galaxy) active @endif">
        <a class="dropdown-toggle" data-toggle="dropdown"  href="javascript:;"
           @if(!$is_galaxy) aria-expanded="false" @else aria-expanded="true" @endif>
            <i class="{{ $top_icon_galaxy }}"></i> {{ $top_title_galaxy }} <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="{{ $uniShortCode }}/galaxy/1/1"><i class="icon-globe"></i> <?php echo $lang->trans('ogniter.og_galaxy_view')?></a></li>
            <li><a href="{{ $uniShortCode }}/track/moons/1"><i class="icon-adjust"></i> <?php echo $lang->trans('ogniter.moons')?></a></li>
            <li><a href="{{ $uniShortCode }}/track/player-status/1/i"><i class="icon-question-sign"></i> <?php echo $lang->trans('ogniter.planet_search_by_status')?></a></li>
            <li><a href="{{ $uniShortCode }}/track/free-slots/1/0"><i class="icon-map-marker"></i> <?php echo $lang->trans('ogniter.og_colonize')?></a></li>
            <li><a href="{{ $uniShortCode }}/track/bandits-emperors/1/2"><i class="icon-fast-forward"></i> <?php echo $lang->trans('ogniter.find_bandits_emperors')?></a></li>
        </ul>
    </li>
    <li @if($is_comparison) class="active" @endif>
        <a href="{{ $uniShortCode }}/comparison"
           @if(!$is_comparison) aria-expanded="false" @else aria-expanded="true" @endif>
            <i class="icon-random"></i> {{ $lang->trans('ogniter.og_comparison') }}</a>
    </li>
    <li class="dropdown @if($is_tools) active @endif">
        <a class="dropdown-toggle" data-toggle="dropdown"  href="javascript:;"
           @if(!$is_tools) aria-expanded="false" @else aria-expanded="true" @endif>
            <i class="{{ $top_icon_tools }}"></i> {{ $top_title_tools }} <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="{{ $uniShortCode }}/flight_times"><i class="icon-time"></i> <?php echo $lang->trans('ogniter.og_flight_times')?></a></li>
        </ul>
    </li>
    <li class="dropdown @if($is_index) active @endif">
        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;"
           @if(!$is_index) aria-expanded="false" @else aria-expanded="true" @endif>
            <i class="{{ $top_icon_index }}"></i> {{ $top_title_index }} <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="{{ $uniShortCode }}"><i class="icon-info-sign"></i> {{ $currentUniverse->local_name }}</a></li>
            <li><a href="{{ $uniShortCode }}/banned_users"><i class="icon-warning-sign"></i> Banned users</a></li>
            <li><a href="{{ $uniShortCode }}/evolution"><i class="icon-align-right"></i> Evolution</a></li>
        </ul>
    </li>
</ul>