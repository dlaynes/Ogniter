@extends('classic.layouts.default')

@section('title'){{ $page_title.' - '.$currentCountry->domain.', '.$lang_types[$type] }}@stop
@section('description'){{ $page_description }}@stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $currentCountry->language }}">
                <i class="flag flag-{{ $currentCountry->flag }}"></i>
                {{ $currentCountry->domain }}</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $currentPath }}">{{ $module_name.', '.$lang_types[$type]. ($mode=='normal' ? ' (Normal)' : ' (Special)') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.domains.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $module_name . ' - '. $currentCountry->domain.', '.$lang_types[$type] }}
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
                            <li><a href="{{ $currentCountry->language }}/top/players/<?php echo $type,'/',$mode?>"><?php echo $lang->trans('ogniter.og_player')?></a></li>
                            <li><a href="{{ $currentCountry->language }}/top/alliances/<?php echo $type,'/',$mode?>"><?php echo $lang->trans('ogniter.og_alliance')?></a></li>
                        </ul>
                    </div>

                    <div class="btn-group"><a class="btn<?php if($type==0){ echo ' btn-info';}?>" href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/0/<?php echo $mode?>"><?php echo $lang_types[0]?></a></div>
                    <div class="btn-group"><a class="btn<?php if($type==1){ echo ' btn-info';}?>" href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/1/<?php echo $mode?>"><?php echo $lang_types[1]?></a></div>
                    <div class="btn-group"><a class="btn<?php if($type==2){ echo ' btn-info';}?>" href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/2/<?php echo $mode?>"><?php echo $lang_types[2]?></a></div>
                    <div class="btn-group"><?php $text = ($type > 2 && $type < 7)? $lang_types[$type] : $lang_types[3]; ?>
                        <a class="btn <?php if($type > 2 && $type < 7){ echo ' btn-info'; }?> dropdown-toggle" data-toggle="dropdown" href="javascript:;"><?php echo $text?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/3/<?php echo $mode?>"><?php echo $lang_types[3]?></a></li>
                            <li><a href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/4/<?php echo $mode?>"><?php echo $lang_types[4]?></a></li>
                            <li><a href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/5/<?php echo $mode?>"><?php echo $lang_types[5]?></a></li>
                            <li><a href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/6/<?php echo $mode?>"><?php echo $lang_types[6]?></a></li>
                        </ul>
                    </div>
                    <div class="btn-group"><a class="btn<?php if($type==7){ echo ' btn-info';}?>" href="{{ $currentCountry->language }}/top/<?php echo $cat_name?>/7/<?php echo $mode?>"><?php echo $lang_types[7]?></a></div>
                </div>
                <hr />
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
        @include('classic.partials.shared.statistics')
        @include('classic.partials.twitter')
    </div>
@endsection