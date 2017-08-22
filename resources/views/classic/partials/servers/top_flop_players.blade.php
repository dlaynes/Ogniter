<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>
    <tr><td colspan="4"><h4><?php echo str_replace('%n%',$per_page,$tf_desc)?> (<?php echo $type_name?>) - <?php echo $range_desc?>
                - <span class="text-warning"><?php if($range!='by_day') { echo date('Y-m-d', $previous_server_update).' >'; }?> <?php echo date('Y-m-d', $last_server_update)?></span></h4>
        <p>&nbsp;</p></td>
    </tr>
    <tr>
        <th>-</th>
        <th><?php echo $lang->trans('ogniter.og_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <?php /*<th><?php echo $lang->trans('ogniter.og_alliance')?></th> */ ?>
        <?php if($type==0){?><th><?php echo $lang->trans('ogniter.og_score')?></th><?php } ?>
        <?php if($type==3){?><th><?php echo $lang->trans('ogniter.og_num_ships')?></th><?php } ?>
        <th>+/-</th>
        <th><?php echo $lang->trans('ogniter.statistics')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach($ranking_results as $result){
    $string_status = \App\Ogniter\Model\Ogame\Player::numberToStatus($result->player_status);
    ?>
    <tr id="player<?php echo $result->player_id?>">
        <td><?php echo $i++?></td>
        <td><?php echo $result->position?></td>
        <td><a href="<?php echo $uniShortCode?>/player/<?php echo $result->player_id?>"><?php echo e($result->player_name).($result->player_status?' ('.$string_status.')':'')?></a>
        </td>
        <?php /*<td>
					<?php if($result['alliance_tag']){ ?>
					<a href="<?php echo $uniShortCode?>/alliance/<?php echo $result['og_alliance_id']?>"><?php echo chars_utf8($result['alliance_tag'])?></a>
					<?php } ?>
				</td> */ ?>
        <?php if($type!=3){?><td><?php echo number_format($result->score)?></td><?php } ?>
        <?php if($type==3){?><td><?php echo number_format($result->ships)?></td><?php } ?>
        <td><?php echo $tagsHelper->parseDifference($result->difference)?></td>
        <td><a href="<?php echo $uniShortCode.'/statistics/1/'.$type.'/month/'.$result->player_id?>" class="label label-info"><span class="icon-signal icon-white"></span> <?php echo $lang->trans('ogniter.view')?></a></td>
    </tr>
    <?php }	?>
    </tbody>
</table>