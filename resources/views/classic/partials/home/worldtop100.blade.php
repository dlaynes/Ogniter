<?php $span = isset($span) ? $span:'span6' ?>
<div class="well clearfix above-me">
    <a class="{{ $span }} top-block" href="site/top/players/0/special">
        <span class="icon32 icon-red icon-user"></span>
        <div>{{ str_replace('%n%',100,$lang->trans('ogniter.ogame_top_n_players') ) }} (Special)</div>
    </a>
    <a class="{{ $span }} top-block" href="site/top/alliances/0/special">
        <span class="icon32 icon-color icon-arrow-4diag"></span>
        <div>{{ str_replace('%n%',100,$lang->trans('ogniter.ogame_top_n_alliances') ) }} (Special)</div>
    </a>
    <a class="{{ $span }} top-block" href="site/top/players/0/normal" style="margin-left: 0">
        <span class="icon32 icon-user"></span>
        <div>{{ str_replace('%n%',100,$lang->trans('ogniter.ogame_top_n_players') ) }} (Normal)</div>
    </a>
    <a class="{{ $span }} top-block" href="site/top/alliances/0/normal">
        <span class="icon32 icon-arrow-4diag"></span>
        <div>{{ str_replace('%n%',100,$lang->trans('ogniter.ogame_top_n_alliances') ) }} (Normal)</div>
    </a>
</div>