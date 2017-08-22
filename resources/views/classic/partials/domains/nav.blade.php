<ul class="nav nav-tabs" style="border-bottom: 0 none">
    <li @if($is_index) class="active" @endif >
        <a href="{{ $currentCountry->language }}"
           @if(!$is_index) aria-expanded="false" @else  aria-expanded="true" @endif>
            <i class="icon-th-list"></i> {{ $currentCountry->domain }}</a></li>
    <li @if($is_evolution) class="active" @endif >
        <a href="{{ $currentCountry->language }}/country-evolution"
           @if(!$is_evolution) aria-expanded="false" @else  aria-expanded="true" @endif>
            <i class="icon-align-right"></i>
            Evolution</a></li>
    <li @if($is_search) class="active" @endif >
        <a href="{{ $currentCountry->language }}/search"
           @if(!$is_search) aria-expanded="false" @else  aria-expanded="true" @endif>
            <i class="icon-search"></i> {{ $lang->trans('ogniter.og_search')  }}</a></li>
    <li class="dropdown
        @if($is_special_player || $is_special_alliance
            || $is_normal_player || $is_normal_alliance) active @endif " >
        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
            <i class="glyphicon {{ $top_icon }}"></i> {{ $top_title }} <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li @if($is_special_player) class="active" @endif >
                <a href="{{ $currentCountry->language }}/top/players/0/special"
                   @if(!$is_special_player) aria-expanded="false" @else aria-expanded="true" @endif>
                    <i class="icon-user"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_players') )?> (S)</a></li>
            <li @if($is_special_alliance) class="active" @endif >
                <a href="{{ $currentCountry->language }}/top/alliances/0/special"
                   @if(!$is_special_alliance) aria-expanded="false" @else aria-expanded="true" @endif>
                    <i class="icon-screenshot"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_alliances') )?> (S)</a></li>
            <li @if($is_normal_player) class="active" @endif >
                <a href="{{ $currentCountry->language }}/top/players/0/normal"
                   @if(!$is_normal_player) aria-expanded="false" @else aria-expanded="true" @endif>
                    <i class="icon-user"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_players') )?> (N)</a></li>
            <li @if($is_normal_alliance) class="active" @endif >
                <a href="{{ $currentCountry->language }}/top/alliances/0/normal"
                   @if(!$is_normal_alliance) aria-expanded="false" @else aria-expanded="true" @endif>
                    <i class="icon-screenshot"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_alliances') )?> (N)</a></li>
        </ul>
    </li>
</ul>