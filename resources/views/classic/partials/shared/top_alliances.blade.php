<table class="table table-striped table-condensed">
    <thead>
    <tr>
        <th>#</th>
        <th><?php echo $lang->trans('ogniter.og_alliance_tag')?></th>
        <th><?php echo $lang->trans('ogniter.og_name')?></th>
        <th><?php echo $lang->trans('ogniter.og_universe')?></th>
        <th><?php echo $lang->trans('ogniter.uni_position')?></th>
        <th><?php echo $lang->trans('ogniter.og_score')?></th>
        <th><?php echo $lang->trans('ogniter.statistics')?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 1;
    foreach($records as $alliance){
    ?>
    <tr>
        <td><?php echo $i?></td>
        <td><a href="{{ $alliance->language.'/'.$alliance->universe_id.'/alliance/'.$alliance->alliance_id }}">{{ $alliance->tag }}</a></td>
        <td>{{ $alliance->name  }}</td>
        <td><a href="<?php echo $alliance->language,'/',$alliance->universe_id,'/galaxy'?>"><?php echo $alliance->local_name,' (',$alliance->language,' ',$alliance->number,')'?></a></td>
        <td><a href="<?php echo $alliance->language,'/',$alliance->universe_id,'/ranking/2/0/position/ASC/',(floor($alliance->ranking_position/100 )*100),'#alliance',$alliance->alliance_id?>"><?php echo $alliance->ranking_position?></a></td>
        <td><?php echo number_format($alliance->ranking_score)?></td>
        <td><a href="<?php echo $alliance->language,'/',$alliance->universe_id,'/statistics/2/0/month/',$alliance->alliance_id?>" class="label label-info"><span class="icon-signal"></span> <?php echo $lang->trans('ogniter.view')?></a></td>
    </tr>
    <?php
    $i++;
    } ?>
    </tbody>
</table>