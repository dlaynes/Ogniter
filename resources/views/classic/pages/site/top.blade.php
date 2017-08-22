@extends('classic.layouts.default')

@section('title'){{ $page_title.', '.$lang_types[$type] }}@stop
@section('description'){{ $page_description }}@stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $currentPath }}">{{ $module_name.', '.$lang_types[$type].' - Ogame '. ($mode=='normal' ? '(Normal)' : 'Special') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        <ul class="nav nav-tabs" style="border-bottom: 0 none">
            <li @if($is_special_player) class="active" @endif >
                <a href="site/top/players/0/special"
                   @if(!$is_special_player) aria-expanded="false" @else  aria-expanded="false" @endif>
                    <i class="icon-user" title="# of players"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_players') )?> (S)</a></li>
            <li @if($is_special_alliance) class="active" @endif >
                <a href="site/top/alliances/0/special"
                   @if(!$is_special_alliance) aria-expanded="false" @else  aria-expanded="false" @endif>
                    <i class="icon-screenshot"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_alliances') )?> (S)</a></li>
            <li @if($is_normal_player) class="active" @endif >
                <a href="site/top/players/0/normal"
                   @if(!$is_normal_player) aria-expanded="false" @else  aria-expanded="false" @endif>
                    <i class="icon-user" title="# of players"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_players') )?> (N)</a></li>
            <li @if($is_normal_alliance) class="active" @endif >
                <a href="site/top/alliances/0/normal"
                    @if(!$is_normal_alliance) aria-expanded="false" @else  aria-expanded="false" @endif>
                    <i class="icon-screenshot"></i> <?php echo str_replace('%n%',100,$lang->trans('ogniter.top_n_alliances') )?> (N)</a></li>
        </ul>
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $module_name.', '.$lang_types[$type] }} - Ogame
                    @if($mode=='normal')
                        (Normal)
                    @else
                        (Special)
                    @endif</h2>
            </div>
            <div class="box-content">
                <div class="btn-toolbar">

                    <div class="btn-group">
                        <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
                            <?php echo ($category==1)?$lang->trans('ogniter.og_player'):$lang->trans('ogniter.og_alliance')?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="site/top/players/<?php echo $type,'/',$mode?>"><?php echo $lang->trans('ogniter.og_player')?></a></li>
                            <li><a href="site/top/alliances/<?php echo $type,'/',$mode?>"><?php echo $lang->trans('ogniter.og_alliance')?></a></li>
                        </ul>
                    </div>

                    <div class="btn-group"><a class="btn<?php if($type==0){ echo ' btn-info';}?>" href="site/top/<?php echo $cat_name?>/0/<?php echo $mode?>"><?php echo $lang_types[0]?></a></div>
                    <div class="btn-group"><a class="btn<?php if($type==1){ echo ' btn-info';}?>" href="site/top/<?php echo $cat_name?>/1/<?php echo $mode?>"><?php echo $lang_types[1]?></a></div>
                    <div class="btn-group"><a class="btn<?php if($type==2){ echo ' btn-info';}?>" href="site/top/<?php echo $cat_name?>/2/<?php echo $mode?>"><?php echo $lang_types[2]?></a></div>
                    <div class="btn-group"><?php $text = ($type > 2 && $type < 7)? $lang_types[$type] : $lang_types[3]; ?>
                        <a class="btn <?php if($type > 2 && $type < 7){ echo ' btn-info'; }?> dropdown-toggle" data-toggle="dropdown" href="javascript:;"><?php echo $text?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="site/top/<?php echo $cat_name?>/3/<?php echo $mode?>"><?php echo $lang_types[3]?></a></li>
                            <li><a href="site/top/<?php echo $cat_name?>/4/<?php echo $mode?>"><?php echo $lang_types[4]?></a></li>
                            <li><a href="site/top/<?php echo $cat_name?>/5/<?php echo $mode?>"><?php echo $lang_types[5]?></a></li>
                            <li><a href="site/top/<?php echo $cat_name?>/6/<?php echo $mode?>"><?php echo $lang_types[6]?></a></li>
                        </ul>
                    </div>
                    <div class="btn-group"><a class="btn<?php if($type==7){ echo ' btn-info';}?>" href="site/top/<?php echo $cat_name?>/7/<?php echo $mode?>"><?php echo $lang_types[7]?></a></div>
                </div>
                <hr />

                <div>
                    @if($category==1)
                        @include('classic.partials.shared.top_players', ['records'=>$records])
                    @else
                        @include('classic.partials.shared.top_alliances', ['records'=>$records])
                    @endif
                </div>
            </div>
        </div>
        @include('classic.partials.disqus')
    </div>

    <div class="span3">
        @include('classic.partials.shared.poll_form')
        @include('classic.partials.shared.statistics')
    </div>
@endsection