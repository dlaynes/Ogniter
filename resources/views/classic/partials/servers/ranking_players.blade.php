<?php
/*
$base_link = $uniShortCode.'/highscore/players/'.$type;

if($order_by=='position'){
    $position_link = $base_link.'?order_by=position&order='.(($order=='DESC')?'ASC':'DESC');
} else {
    $position_link = $base_link.'?order_by=position&order=ASC';
}
if($order_by=='weekly_difference'){
    $weekly_link = $base_link.'?order_by=weekly_difference&order='.(($order=='DESC')?'ASC':'DESC');
} else {
    $weekly_link = $base_link.'?order_by=weekly_difference&order=DESC';
}
if($order_by=='monthly_difference'){
    $monthly_link = $base_link.'?order_by=monthly_difference&order='.(($order=='DESC')?'ASC':'DESC');
} else {
    $monthly_link = $base_link.'?order_by=monthly_difference&order=DESC';
}
if($order_by=='ships'){
    $ships_link = $base_link.'?order_by=ships&order='.(($order=='DESC')?'ASC':'DESC');
} else {
    $ships_link = $base_link.'?order_by=ships&order=DESC';
} */

if($result_count > 251){
    $low_250 = $result_count - 251;
} else{
    $low_250 = 0;
}
if($result_count > 101){
    $low_100 = $result_count - 101;
} else{
    $low_100 = 0;
}
if($result_count > 11){
    $low_10 = $result_count - 11;
} else{
    $low_10 = 0;
}
?>
<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <?php
    if($currentUniverse->api_enabled){
    $diff = time()-$last_update;
    ?>
    <tr>
        <td colspan="<?php if($type==3){ echo '8'; } else { echo '7'; } ?>">
            <div class="pull-left">
                <?php echo $lang->trans('ogniter.last_update').': '.$tagsHelper->parseTime($diff)?>
            </div>
            <div class="pull-right">
                <?php echo $lang->trans('ogniter.next_update').': '.$tagsHelper->parseTime(86400 - $diff, \FALSE)?>
            </div>
            <p>&nbsp;</p>
        </td>
    </tr>
    <?php }?>
    <tr>
        <?php if($order_by!='position'){?><th>#</th><?php } ?>
        <th><?php echo $lang->trans('ogniter.og_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_alliance')?></th>
        <th><?php echo $lang->trans('ogniter.og_score')?></th>
        <th><?php echo $lang->trans('ogniter.og_weekly_increment')?></th>
        <th><?php echo $lang->trans('ogniter.og_monthly_increment')?></th>
        <?php if($type==3){?><th><?php echo $lang->trans('ogniter.og_num_ships')?></th><?php } ?>
        <th><?php echo $lang->trans('ogniter.statistics')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $pos = $offset;
    foreach($ranking_results as $result){
    $dif = $dif_m = '-';

            $pos++;
    $c =  $result->weekly_position - $result->position;
    if($c!=0){
        $dif = $tagsHelper->parseDifference($c);
    }
    $c = $result->monthly_position - $result->position;
    if($c!=0){
        $dif_m = $tagsHelper->parseDifference($c);
    } ?>
    <tr id="player<?php echo $result->entity_id?>">
        <?php if($order_by!='position'){?><td><?php echo $pos?></td><?php } ?>
        <td><?php echo $result->position?> [ <?php echo $dif?> ] [ <?php echo $dif_m?> ]</td>
        <td><a href="<?php echo $uniShortCode?>/player/<?php echo $result->entity_id?>"><?php echo e($result->player_name).($result->player_status?' ('.$result->string_status.')':'')?></a>
            <?php
            if( strpos($result->string_status, 'i') ===FALSE
                    && strpos($result->string_status, 'I')===FALSE &&
                    strpos($result->string_status, 'a') ===FALSE
            ){
                $honor_desc = '';

                if($type==7){
                    $honor_position = $result->position;
                    $honor_score = $result->score;
                } else {
                    $honor = explode(',', $result->honor_info);
                    $honor_position = $honor[0];
                    $honor_score = isset($honor[1])? $honor[1] : 0;
                }

                //Is he an emperor?
                if($honor_position < 11 && $honor_score > 14999 ){
                    $honor_desc = ' &nbsp; <i class="icon-star-empty" title="'.$lang->trans('ogniter.grand_emperor').'"></i>';
                } elseif($honor_position < 101 && $honor_score > 2499){
                    $honor_desc = ' &nbsp; <i class="icon-star-empty" title="'.$lang->trans('ogniter.emperor').'"></i>';
                } elseif($honor_position < 251 && $honor_score > 499){
                    $honor_desc = ' &nbsp; <i class="icon-star-empty" title="'.$lang->trans('ogniter.star_lord').'"></i>';
                }
                //Is he a bandit??
                elseif($honor_position > $low_10 && $honor_score < -14999){
                    $honor_desc = ' &nbsp; <i class="icon-star" title="'.$lang->trans('ogniter.bandit_king').'"></i>';
                }
                elseif($honor_position > $low_100 && $honor_score < -2499){
                    $honor_desc = ' &nbsp; <i class="icon-star" title="'.$lang->trans('ogniter.bandit_lord').'"></i>';
                }
                elseif($honor_position > $low_250 && $honor_score < -499){
                    $honor_desc = ' &nbsp; <i class="icon-star" title="'.$lang->trans('ogniter.bandit').'"></i>';
                }
                echo $honor_desc;
            } ?>
        </td>
        <td>
            <?php if($result->alliance_tag){ ?>
            <a href="<?php echo $uniShortCode?>/alliance/<?php echo $result->alliance_id?>"><?php echo e($result->alliance_tag)?></a>
            <?php } ?>
        </td>
        <td><?php echo number_format($result->score)?></td>
        <td><?php echo $tagsHelper->parseDifference($result->weekly_difference)?></td>
        <td><?php echo $tagsHelper->parseDifference($result->monthly_difference)?></td>
        <?php if($type==3){?><td><?php echo $result->ships?></td><?php } ?>
        <td><a href="<?php echo $uniShortCode.'/statistics/1/'.$type.'/month/'.$result->entity_id?>" class="label label-info"><span class="icon-signal icon-white"></span> <?php echo $lang->trans('ogniter.view')?></a></td>
    </tr>
    <?php }	?>
    </tbody>
</table>
