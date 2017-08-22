<?php $time = time()?>
<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <tr><td colspan="6"><h4><?php echo $lang->trans('ogniter.og_search_results_by').' '.$lang->trans('ogniter.og_planet')?></h4></td></tr>
    <tr>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_type')?></th>
        <th><?php echo $lang->trans('ogniter.og_location')?></th>
        <th><?php echo $lang->trans('ogniter.og_player')?></th>
        <th><?php echo $lang->trans('ogniter.og_position')?></th>
        <th><?php echo $lang->trans('ogniter.last_update')?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($search_results as $result){ ?>
    <tr>
        <td><?php echo e($result->name) ?></td>
        <td><?php if($result->type==1){ echo $lang->trans('ogniter.og_planet');} else { echo $lang->trans('ogniter.og_moon');}?></td>
        <td><a href="<?php echo $uniShortCode?>/galaxy/<?php echo $result->galaxy.'/'.$result->system?>"
            ><?php echo $result->galaxy.':'.$result->system.':'.$result->position?></a></td>
        <td><a href="<?php echo $uniShortCode?>/player/<?php echo $result->player_id?>"
            ><?php echo e($result->player_name).($result->string_status?' ('.$result->string_status.')':'')?></a></td>
        <td><?php echo $result->ranking_position?></td>
        <td><?php echo $tagsHelper->parseTime($time - $result->last_update)?></td>
    </tr>
    <?php }	?>
    </tbody>
</table>
