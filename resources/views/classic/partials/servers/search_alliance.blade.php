<?php $time = time() ?>
<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <tr><td colspan="6"><h4><?php echo $lang->trans('ogniter.og_search_results_by')?>
                <?php echo ($search_by=='alliance')? $lang->trans('ogniter.og_alliance'):$lang->trans('ogniter.og_alliance_tag_long');?></h4></td></tr>
    <tr>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_alliance_tag')?></th>
        <th><?php echo $lang->trans('ogniter.og_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_members')?></th>
        <th><?php echo $lang->trans('ogniter.last_update')?></th>
        <th><?php echo $lang->trans('ogniter.alliance_planets')?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($search_results as $result){
    $pos = (floor($result->ranking_position/100 )*100);
    ?>
    <tr>
        <td><?php echo e($result->name)?></td>
        <td><a href="<?php echo $uniShortCode?>/alliance/<?php echo $result->alliance_id?>"><?php echo e($result->tag)?></a></td>
        <td><?php echo '<a href="',$uniShortCode,'/ranking/2/0/position/ASC/',$pos,'">',$result->ranking_position,'</a>'?></td>
        <td><?php echo $result->ally_members?></td>
        <td><?php echo $tagsHelper->parseTime($time - $result->last_update)?></td>
        <td><a href="<?php echo $uniShortCode,'/track/alliance/1/',$result->alliance_id?>"
               class="label label-warning"><span class="icon-signal icon-globe"></span> <?php echo $lang->trans('ogniter.view')?></a></td>
    </tr>
    <?php }	?>
    </tbody>
</table>
