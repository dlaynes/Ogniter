<?php $title = str_replace('%n%',$per_page,$tf_desc).' ('.$type_name.')' ?>
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
            <a href="{{ $currentPath }}">{{ $title.', '.$range_desc  }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i>
                    {{ $title . ' - ' . $currentUniverse->local_name.', '.$range_desc }}</h2>
            </div>
            <div class="box-content above-me">
                <div class="btn-toolbar pull-left">

                    <div class="btn-group">
                        <a class="btn dropdown-toggle btn-success" data-toggle="dropdown" href="#">
                            <?php echo ($order=='DESC')?$lang->trans('ogniter.top'):$lang->trans('ogniter.flop')?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $uniShortCode?>/top_flop/DESC/<?php echo $category,'/',$type?>?range=<?php echo $range?>"><?php echo $lang->trans('ogniter.top')?></a></li>
                            <li><a href="<?php echo $uniShortCode?>/top_flop/ASC/<?php echo $category,'/',$type?>?range=<?php echo $range?>"><?php echo $lang->trans('ogniter.flop')?></a></li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
                            <?php echo ($category==1)?$lang->trans('ogniter.og_player'):$lang->trans('ogniter.og_alliance')?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $uniShortCode?>/top_flop/<?php echo $order?>/1/<?php echo $type?>?range=<?php echo $range?>"><?php echo $lang->trans('ogniter.og_player')?></a></li>
                            <li><a href="<?php echo $uniShortCode?>/top_flop/<?php echo $order?>/2/0?range=<?php echo $range?>"><?php echo $lang->trans('ogniter.og_alliance')?></a></li>
                        </ul>
                    </div>

                    <div class="btn-group"><a class="btn<?php if($type==0){ echo ' btn-info';}?>" href="<?php echo $uniShortCode,'/top_flop/', $order,'/',$category?>/0?range=<?php echo $range?>"><?php echo $dtypes[0]?></a></div>
                    <?php if($category==1) { ?>
                    <div class="btn-group"><a class="btn<?php if($type==3){ echo ' btn-info';}?>" href="<?php echo $uniShortCode,'/top_flop/', $order,'/',$category?>/3?range=<?php echo $range?>"><?php echo $dtypes[3]?></a></div>
                    <?php } ?>

                    <div class="btn-group">
                        <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                            {{ $range_desc }}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $uniShortCode?>/top_flop/<?php echo $order.'/'. $category.'/'.$type?>?range=by_day"><?php echo $lang->trans('ogniter.by_day')?></a></li>
                            <li><a href="<?php echo $uniShortCode?>/top_flop/<?php echo $order.'/'. $category.'/'.$type?>?range=by_week"><?php echo $lang->trans('ogniter.by_week')?></a></li>
                            <li><a href="<?php echo $uniShortCode?>/top_flop/<?php echo $order.'/'. $category.'/'.$type?>?range=by_month"><?php echo $lang->trans('ogniter.by_month')?></a></li>
                        </ul>
                    </div>


                </div>

                <div class="clearfix"></div>
                <hr />
                <div>
                    @include('classic.partials.servers.top_flop_'.$category_name,
                        [
                        'ranking_results'=>$top_flop,'type_name'=>$type_name, 'tf_desc'=>$tf_desc, 'range' => $range,
                        'range_desc' => $range_desc, 'previous_server_update' => $previous_server_update,
                        'per_page'=>$per_page, 'last_server_update'=>$last_server_update, 'tagsHelper'=>$tagsHelper])
                </div>
            </div>
        </div>
    </div>
    <div class="span3">
        @include('classic.partials.shared.statistics')
        @include('classic.partials.twitter')
    </div>

@endsection