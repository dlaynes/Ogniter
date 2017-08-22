<?php $time = time()?>
<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <tr><td colspan="6"><h4><?php echo $lang->trans('ogniter.og_search_results_by').' '.$lang->trans('ogniter.og_player')?></h4></td></tr>
    <tr>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_alliance')?></th>
        <th><?php echo $lang->trans('ogniter.last_update')?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($search_results as $result){
    $pos = (floor($result->ranking_position/100 )*100);
    ?>
    <tr>
        <td><a href="<?php echo $uniShortCode?>/player/<?php echo $result->player_id?>"><?php echo e($result->name).($result->status? ' ('.$result->string_status.')':'')?></a></td>
        <td><?php echo '<a href="'.$uniShortCode.'/ranking/1/0/position/ASC/'.$pos.'">'.$result->ranking_position.'</a>'?></td>
        <td>
            <?php if($result->alliance_tag){ ?>
            <a href="<?php echo $uniShortCode?>/alliance/<?php echo $result->alliance_id?>"><?php echo e($result->alliance_tag)?></a>
            <?php } else{
                echo '&nbsp;';
            } ?>
        </td>
        <td><?php echo $tagsHelper->parseTime($time - $result->last_update)?></td>
    </tr>
    <?php }	?>
    </tbody>
</table>