@extends('classic.layouts.default')
@section('title'){{ $PAGE_TITLE }}@endsection
@section('description'){{ $PAGE_DESCRIPTION }}@endsection

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
        <li>
            <a href="{{ $currentCountry->language }}">
                <i class="flag flag-{{ $currentCountry->flag }}"></i>
                {{ $currentCountry->domain }}</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $uniShortCode }}">{{ $currentUniverse->local_name }}</a>  <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $uniShortCode }}/banned_users">Banned Users</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> Banned Users - {{ $currentUniverse->local_name }}</h2>
            </div>
            <div class="box-content above-me">

                <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <?php
                    if($currentUniverse->api_enabled){
                    $diff = time()-$last_update;
                    ?>
                    <tr>
                        <td colspan="4>">
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
                        <th><?php echo $lang->trans('ogniter.og_name')?></th>
                        <th><?php echo $lang->trans('ogniter.og_position')?></th>
                        <th>Added on</th>
                        <th>Restored on</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!count($results)){ ?>
                    <tr>
                        <td colspan="4" class="text-warning">No data available</td></tr>
                    <?php }

                    foreach($results as $result){

                    $currently_banned = !$result->removed_on;
                    //Weird stuff.
                    if($currently_banned){
                        $currently_banned = $result->status & \App\Ogniter\Model\Ogame\Player::STATUS_BANNED;
                    } ?>
                    <tr>
                        <td class="<?php echo $currently_banned ? 'text-warning' : 'text-success'?>"><strong><?php echo htmlspecialchars($result->name)?></strong> <?php echo ($result->status? ' ('.\App\Ogniter\Model\Ogame\Player::numberToStatus($result->status).')':'')?> </td>
                        <td><?php echo $result->position? $result->position : '--'?></td>
                        <td><?php echo date('Y/m/d',$result->added_on)?></td>
                        <td><?php echo $result->removed_on? date('Y/m/d',$result->removed_on) : '--'?></td>
                    </tr>
                    <?php }	?>
                    </tbody>
                </table>

                <?php
                if($result_count){
                    echo $pager->render();
                } ?>
                <small class="text-info">
                    * Data from May/06/2016 is not accurate, and references a previous day.
                </small>

            </div>
        </div>
        <div class="box above-me">
            <div class="box-content">
                @include('classic.partials.disqus')
            </div>
        </div>
    </div>
    <div class="span3">
        @include('classic.partials.servers.sidebar')
        @include('classic.partials.twitter')
    </div>
@endsection