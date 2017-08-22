<?php
if($entity->num_players > 0){
    $percent_normal = ($entity->normal_players / $entity->num_players) * 100;
    $percent_suspended = ($entity->suspended_players / $entity->num_players) * 100;
    $percent_inactive = ($entity->inactive_players / $entity->num_players) * 100;
    $percent_inactive_30 = ($entity->inactive_30_players / $entity->num_players) * 100;
    $percent_vacation = ($entity->vacation_players / $entity->num_players) * 100;
    $percent_outlaw = ($entity->outlaw_players / $entity->num_players) * 100;
} else {
    $percent_normal = 0;
    $percent_inactive = 0;
    $percent_inactive_30 = 0;
    $percent_vacation = 0;
    $percent_suspended = 0;
    $percent_outlaw = 0;
}
?>
<div class="box">
<div class="box-header well">
    <h2 {!! isset($currentCountry) ? 'class="server-'.$currentCountry->language.'"':'' !!}><i class="icon-align-right"></i>
        <a href="{{ $statisticsLink }}">{{ $statisticsTitle }}</a></h2>
</div>
<div class="box-content clearfix">
    <h4>{{ date('Y/m/d',strtotime($entity->added_on)) }}</h4><br />
    <ul class="dashboard unstyled">
        <li><table class="table table-bordered table-condensed table-striped">
                <tr><td><strong class="text-success">{{ $lang->trans('ogniter.og_num_players') }}</strong>
                        <br />{{ number_format($entity->num_players) }}</td>
                    <td><strong class="text-success">{{ $lang->trans('ogniter.og_num_alliances') }}</strong>
                        <br />{{ number_format($entity->num_alliances) }}</td></tr>
                <tr><td><strong class="text-info">{{ $lang->trans('ogniter.planets') }}</strong>
                        <br />{{ number_format($entity->num_planets) }}</td>
                    <td><strong class="text-info">{{ $lang->trans('ogniter.moons') }}</strong>
                        <br />{{ number_format($entity->num_moons) }}</td></tr>
            </table></li>
        <li><h4>{{ $lang->trans('ogniter.statistics').' ('.$lang->trans('ogniter.og_player').')' }}</h4></li>
        <li>&nbsp;</li>
        <li>{{ $lang->trans('ogniter.normal') }}: <span class="text-info">{{ number_format($entity->normal_players) }}</span>
            <span class="text-warning">({{ number_format($percent_normal,2) }}%)</span></li>
        <li><div class="progress progress-info progress-striped small-progress">
                <div class="bar" style="width: <?php echo $percent_normal?>%"></div>
            </div></li>
        <li>{{ $lang->trans('ogniter.og_outlaw') }}: <span class="text-info">{{ number_format($entity->outlaw_players) }}</span>
            <span class="text-warning">({{ number_format($percent_outlaw,2) }}%)</span></li>
        <li><div class="progress progress-striped small-progress">
                <div class="bar" style="width: <?php echo $percent_outlaw?>%"></div>
            </div></li>
        <li>{{ $lang->trans('ogniter.og_inactive') }}: <span class="text-info">{{ number_format($entity->inactive_players) }}</span>
            <span class="text-warning">({{ number_format($percent_inactive,2) }}%)</span></li>
        <li><div class="progress progress-success progress-striped small-progress">
                <div class="bar" style="width: <?php echo $percent_inactive?>%"></div>
            </div></li>
        <li>{{ $lang->trans('ogniter.og_inactive_30') }}: <span class="text-info">{{ number_format($entity->inactive_30_players) }}</span>
            <span class="text-warning">({{ number_format($percent_inactive_30,2) }}%)</span></li>
        <li><div class="progress progress-warning progress-striped small-progress">
                <div class="bar" style="width: <?php echo $percent_inactive_30?>%"></div>
            </div></li>
        <li>{{ $lang->trans('ogniter.og_v_mode') }}: <span class="text-info">{{ number_format($entity->vacation_players) }}</span>
            <span class="text-warning">({{ number_format($percent_vacation,2) }}%)</span></li>
        <li><div class="progress progress-danger progress-striped small-progress">
                <div class="bar" style="width: <?php echo $percent_vacation?>%"></div>
            </div></li>
        <li>{{ $lang->trans('ogniter.og_suspended') }}: <span class="text-info">{{ number_format($entity->suspended_players) }}</span>
            <span class="text-warning">({{ number_format($percent_suspended,2) }}%)</span></li>
        <li><div class="progress progress-striped small-progress">
                <div class="bar" style="width: <?php echo $percent_suspended?>%"></div>
            </div></li>
    </ul>
</div>
</div>