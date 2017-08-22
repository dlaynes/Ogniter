<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <tr><h4><?php echo str_replace('%n%',$per_page,$tf_desc)?> (<?php echo $type_name?>) - <?php echo $range_desc?>
            - <span class="text-warning"><?php if($range!='by_day') { echo date('Y-m-d', $previous_server_update).' >'; }?> <?php echo date('Y-m-d', $last_server_update)?></span></h4>
        <p>&nbsp;</p>
    </tr>
    <tr>
        <th><?php echo $lang->trans('ogniter.og_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_alliance_tag')?></th>
        <?php if($type==0){?><th><?php echo $lang->trans('ogniter.og_score')?></th><?php } ?>
        <?php if($type==3){?><th><?php echo $lang->trans('ogniter.og_num_ships')?></th><?php } ?>
        <th> +/- </th>
        <th><?php echo $lang->trans('ogniter.statistics')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach($ranking_results as $result){ ?>
    <tr id="alliance<?php echo $result->alliance_id?>">
        <td><?php echo $i++?></td>
        <td><?php echo e($result->alliance_name)?></td>
        <td>
            <a href="<?php echo $uniShortCode?>/alliance/<?php echo $result->alliance_id?>"><?php echo e($result->alliance_tag)?></a>
        </td>
        <?php if($type==0){?><td><?php echo number_format($result->score)?></td><?php } ?>
        <td><?php echo $tagsHelper->parseDifference($result->difference)?></td>
        <td>
            <a href="<?php echo $uniShortCode.'/statistics/2/'.$type.'/month/'.$result->alliance_id?>" class="label label-info"><span class="icon-signal icon-white"></span> <?php echo $lang->trans('ogniter.view')?></a>
        </td>
    </tr>
    <?php }	?>
    </tbody>
</table>