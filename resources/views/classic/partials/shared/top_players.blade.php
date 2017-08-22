<table class="table table-striped table-condensed">
    <thead>
    <tr>
        <th>#</th>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_alliance')?></th>
        <th><?php echo $lang->trans('ogniter.og_universe')?></th>
        <th><?php echo $lang->trans('ogniter.uni_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_score')?></th>
        <th><?php echo $lang->trans('ogniter.statistics')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach($records as $player){ ?>
    <tr>
        <td><?php echo $i?></td>
        <td><a href="<?php echo $player->language,'/',$player->universe_id,'/player/',$player->player_id?>"><?php echo $player->name?></a></td>
        <td><a href="<?php echo $player->language,'/',$player->universe_id,'/alliance/',$player->alliance_id?>"><?php echo $player->alliance_tag?></a></td>
        <td><a href="<?php echo $player->language,'/',$player->universe_id,'/galaxy'?>"><?php echo $player->local_name,' (',$player->language,' ',$player->number,')'?></a></td>
        <td><a href="<?php echo $player->language,'/',$player->universe_id,'/ranking/1/0/position/ASC/',(floor($player->ranking_position/100 )*100),'#player',$player->player_id?>"><?php echo $player->ranking_position?></a></td>
        <td><?php echo number_format($player->ranking_score)?></td>
        <td><a href="<?php echo $player->language,'/',$player->universe_id,'/statistics/1/0/month/',$player->player_id?>" class="label label-info"><span class="icon-signal"></span> <?php echo $lang->trans('ogniter.view')?></a></td>
    </tr>
    <?php
    $i++;
    } ?>
    </tbody>
</table>