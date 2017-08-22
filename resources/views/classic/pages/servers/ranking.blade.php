<?php
if($category==1){
    $title = $lang->trans('ogniter.og_results_by_player'). ' ( '. $type_name.' )';
} else{
    $title = $lang->trans('ogniter.og_results_by_alliance'). ' ( '. $type_name.' )';
}
?>
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
            <a href="{{ $uniShortCode }}">{{ $currentUniverse->local_name }}</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $currentPath }}">{{ $title }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span12">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i>
                    {{ $title.' - '.$currentUniverse->local_name }}</h2>
            </div>
            <div class="box-content above-me">

                <div class="btn-toolbar pull-left">
                    <div class="btn-group">
                        <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
                            <?php echo ($category==1)?$lang->trans('ogniter.og_player'):$lang->trans('ogniter.og_alliance')?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $uniShortCode?>/highscore/players/<?php echo $type?>"><?php echo $lang->trans('ogniter.og_player')?></a></li>
                            <li><a href="<?php echo $uniShortCode?>/highscore/alliances/<?php echo $type?>"><?php echo $lang->trans('ogniter.og_alliance')?></a></li>
                        </ul>
                    </div>

                    <div class="btn-group"><a class="btn<?php if($type==0){ echo ' btn-info';}?>" href="<?php echo $uniShortCode,'/highscore/', $category_type?>/0"><?php echo $dtypes[0]?></a></div>
                    <div class="btn-group"><a class="btn<?php if($type==1){ echo ' btn-info';}?>" href="<?php echo $uniShortCode,'/highscore/', $category_type?>/1"><?php echo $dtypes[1]?></a></div>
                    <div class="btn-group"><a class="btn<?php if($type==2){ echo ' btn-info';}?>" href="<?php echo $uniShortCode,'/highscore/', $category_type?>/2"><?php echo $dtypes[2]?></a></div>
                    <div class="btn-group"><?php $text = ($type > 2 && $type < 7)? $dtypes[$type] : $dtypes[3]; ?>
                        <a class="btn <?php if($type > 2 && $type < 7){ echo ' btn-info'; }?> dropdown-toggle" data-toggle="dropdown" href="javascript:;"><?php echo $text?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $uniShortCode,'/highscore/',$category_type?>/3"><?php echo $dtypes[3]?></a></li>
                            <li><a href="<?php echo $uniShortCode,'/highscore/',$category_type?>/4"><?php echo $dtypes[4]?></a></li>
                            <li><a href="<?php echo $uniShortCode,'/highscore/',$category_type?>/5"><?php echo $dtypes[5]?></a></li>
                            <li><a href="<?php echo $uniShortCode,'/highscore/',$category_type?>/6"><?php echo $dtypes[6]?></a></li>
                        </ul>
                    </div>
                    <div class="btn-group"><a class="btn<?php if($type==7){ echo ' btn-info';}?>" href="<?php echo $uniShortCode,'/highscore/', $category_type?>/7"><?php echo $dtypes[7]?></a></div>

                </div>

                <div class="clearfix"></div>
                <hr />
                <div>
                    @include('classic.partials.servers.ranking_'.$category_type,
                    ['last_update'=>$last_update,'ranking_results'=>$ranking_results,'type_name'=>$type_name, 'type'=>$type,
                    'order_by' => $order_by, 'order' => $order, 'offset' => $offset,
                    'result_count'=>$ranking_count, 'tagsHelper'=>$tagsHelper])
                    <?php
                    if($ranking_count){
                        echo $pager->render();
                    } ?>
                </div>
            </div>
        </div>
    </div>
@endsection