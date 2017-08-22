<?php
/*
$base_link = $uniShortCode.'/highscore/alliances/'.$type;

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
        */
?>
<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <?php
    if($currentUniverse->api_enabled){
    $diff = time()-$last_update; ?>
    <tr>
        <td colspan="10">
            <div class="pull-left">
                <?php echo $lang->trans('ogniter.last_update').': '.$tagsHelper->parseTime($diff)?>
            </div>
            <div class="pull-right">
                <?php echo $lang->trans('ogniter.next_update').': '.$tagsHelper->parseTime(86400 - $diff, FALSE)?>
            </div>
            <p>&nbsp;</p>
        </td>
    </tr>
    <?php }?>
    <tr>
        <?php if($order_by!='position'){?><th>#</th><?php } ?>
        <th><?php echo $lang->trans('ogniter.og_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_alliance_tag')?></th>
        <th><?php echo $lang->trans('ogniter.og_members')?></th>
        <th><?php echo $lang->trans('ogniter.og_score')?></th>
        <th><?php echo $lang->trans('ogniter.og_weekly_increment')?></th>
        <th><?php echo $lang->trans('ogniter.og_monthly_increment')?></th>
        <th><?php echo $lang->trans('ogniter.og_average_score_per_player')?></th>
        <th><?php echo $lang->trans('ogniter.statistics')?></th>
        <th><?php echo $lang->trans('ogniter.alliance_planets')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $pos = $offset;
    foreach($ranking_results as $result){
    $dif = '-';
    $dif_m = '-';
    $c = $result->weekly_position - $result->position;
    if($c!=0){
        $dif = $tagsHelper->parseDifference($c);
    }
    $c = $result->monthly_position - $result->position;
    if($c!=0){
        $dif_m = $tagsHelper->parseDifference($c);
    }
            $pos++;
    ?>
    <tr id="alliance<?php echo $result->entity_id?>">
        <?php if($order_by!='position'){?><td><?php echo $pos?></td><?php } ?>
        <td><?php echo $result->position?> [ <?php echo $dif?> ] [ <?php echo $dif_m?> ]</td>
        <td><?php echo e($result->alliance_name)?></td>
        <td>
            <a href="<?php echo $uniShortCode?>/alliance/<?php echo $result->entity_id?>"><?php echo e($result->alliance_tag)?></a>
        </td>
        <td><?php echo $result->ally_members?></td>
        <td><?php echo number_format($result->score)?></td>
        <td><?php echo $tagsHelper->parseDifference($result->weekly_difference)?></td>
        <td><?php echo $tagsHelper->parseDifference($result->monthly_difference)?></td>
        <td><?php if($result->ally_members){
                echo number_format( (int) ($result->score / $result->ally_members) );
            }?></td>
        <td><a href="<?php echo $uniShortCode.'/statistics/2/'.$type.'/month/'.$result->entity_id?>" class="label label-info"><span class="icon-signal icon-white"></span> <?php echo $lang->trans('ogniter.view')?></a></td>
        <td><a href="<?php echo $uniShortCode.'/track/alliance/1/'.$result->entity_id?>" class="label label-warning"><span class="icon-globe icon-white"></span> <?php echo $lang->trans('ogniter.view')?></a></td>
    </tr>
    <?php }	?>
    </tbody>
</table>