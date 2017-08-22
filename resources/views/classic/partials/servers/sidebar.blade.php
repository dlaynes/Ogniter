<div class="box">
    <div class="box-header well">
        <h2><i class="icon-chevron-right icon-white"></i> <?php echo $currentUniverse->local_name?></h2>
    </div>
    <div class="box-content">
        <table class="table-condensed table table-striped">
            <tr><td><i class="icon-search"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/search"> <?php echo $lang->trans('ogniter.og_search')?></a></td></tr>
            <tr><td><i class="icon-question-sign"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/track/player-status/1/i" title="<?php echo $lang->trans('ogniter.by_player_status')?>"> <?php echo $lang->trans('ogniter.planet_search_by_status')?></a></td></tr>
            <tr><td><i class="icon-map-marker"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/track/free-slots/1/0" title="<?php echo $lang->trans('ogniter.og_colonize')?>"> <?php echo $lang->trans('ogniter.og_colonize')?></a></td></tr>
            <tr><td><i class="icon-fast-forward"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/track/bandits-emperors/1/2" title="<?php echo $lang->trans('ogniter.find_bandits_emperors')?>"> <?php echo $lang->trans('ogniter.find_bandits_emperors')?></a></td></tr>
            <tr><td><i class="icon-user"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/highscore/players/0" title="<?php echo $lang->trans('ogniter.players')?>"> <?php echo $lang->trans('ogniter.statistics'),': ',$lang->trans('ogniter.og_player')?></a></td></tr>
            <tr><td><i class="icon-screenshot"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/highscore/alliances/0" title="<?php echo $lang->trans('ogniter.alliances')?>"> <?php echo $lang->trans('ogniter.statistics'),': ',$lang->trans('ogniter.og_alliance')?></a></td></tr>
            <tr><td><i class="icon-retweet"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/top_flop"> <?php echo $lang->trans('ogniter.top').' / '.$lang->trans('ogniter.flop')?></a></td></tr>
            <tr><td><i class="icon-random"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/comparison"> <?php echo $lang->trans('ogniter.og_comparison')?></a></td></tr>
            <tr><td><i class="icon-warning-sign"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/banned_users"> Banned users</a></td></tr>
            <tr><td><i class="icon-align-right"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>/evolution"> Evolution</a></td></tr>
            <tr><td><i class="icon-info-sign"></i></td><td><a href="<?php echo $currentCountry->language.'/'.$currentUniverse->id?>"> <?php echo $lang->trans('ogniter.og_server_info')?></a></td></tr>
        </table>
    </div>
</div>