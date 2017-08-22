<?php
$a = array();
$a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_search');
$a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_search');

$a = str_replace(array('%server%','%domain%'), array($lang->trans('ogniter.all'), $currentCountry->domain), $a);
?>@extends('classic.layouts.default')
@section('title'){{ $a['PAGE_TITLE'] }}@endsection
@section('description'){{ $a['PAGE_DESCRIPTION'] }}@endsection

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/">{{ $lang->trans('ogniter.og_home') }}</a><span class="divider">/</span></li>
        <li>
            <a href="{{ $currentCountry->language }}">
                <i class="flag flag-{{ $currentCountry->flag }}"></i>
                {{ $currentCountry->domain }}</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $currentPath }}">{{ $lang->trans('ogniter.og_search') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.domains.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $lang->trans('ogniter.og_search').' - '.$currentCountry->domain }}</h2>
            </div>
            <div class="box-content">
                <div>
                    {!! Form::open(array('url' => $currentCountry->language.'/search', 'class="form-inline"')) !!}
                    <div class="clearfix center">
                        <label for="server"><?php echo $lang->trans('ogniter.og_server')?>:</label>
                        <select id="server" name="server">
                            <?php foreach($universeList as $universe){ ?>
                            <option value="<?php echo $universe->id?>"><?php echo $universe->local_name?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clearfix center above-me">
                        <label for="galaxy"><?php echo $lang->trans('ogniter.og_search_by')?>:</label>
                        <select class="input-medium" name="search_by" id="search_by">
                            <option value="player"><?php echo $lang->trans('ogniter.og_player')?></option>
                            <option value="planet">><?php echo $lang->trans('ogniter.og_planet')?></option>
                            <option value="tag"><?php echo $lang->trans('ogniter.og_alliance_tag_long')?></option>
                            <option value="alliance"><?php echo $lang->trans('ogniter.og_alliance')?></option>
                        </select>
                        <input type="text" name="name" value="" class="hide" style="display:none" />
                        <label for="search"><?php echo $lang->trans('ogniter.og_text')?>:</label>
                        <input type="text" class="input-medium" name="search" id="search" value="" />
                        &nbsp;
                        <input type="submit" class="submit btn btn-primary" value="<?php echo $lang->trans('ogniter.og_send')?>" />
                        <br />
                        <div class="dnone" id="status_block">&nbsp;<br />
                            <label class="checkbox inline"><input type="checkbox" class="i" name="filters[]" value="i" /> <?php echo $lang->trans('ogniter.og_inactive')?> (i)</label>
                            <label class="checkbox inline"><input type="checkbox" class="i" name="filters[]" value="I" /> <?php echo $lang->trans('ogniter.og_inactive_30')?> (I)</label>
                            <label class="checkbox inline"><input type="checkbox" name="filters[]" value="v" /> <?php echo $lang->trans('ogniter.og_v_mode')?> (v)</label>
                            <label class="checkbox inline"><input type="checkbox" name="filters[]" value="b" /> <?php echo $lang->trans('ogniter.og_suspended')?> (b)</label>
                            <label class="checkbox inline"><input type="checkbox" name="filters[]" value="o" /> <?php echo $lang->trans('ogniter.og_outlaw')?> (o)</label><br />
                            <label class="checkbox inline"><input type="checkbox" name="strict_search" value="v" checked /> <strong>Strict Search?</strong></label>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="span3">
         @include('classic.partials.shared.statistics')
         @include('classic.partials.twitter')
    </div>
@endsection
@section('scripts')
    <script>
        jQuery(document).ready(function(){
            jQuery('#search_by').change(function(){
                if(jQuery(this).val()!='player'){
                    jQuery('#status_block').hide();
                } else{
                    jQuery('#status_block').show();
                }
                //jQuery.uniform.update("input:checkbox, input:radio");
            }).trigger('change');

            var $i = jQuery('.i');
            $i.change(function(){
                var self = this;
                if(jQuery(self).prop('checked')){
                    $i.not(self).prop('checked', false);
                }
                jQuery.uniform.update("input:checkbox, input:radio");
            });
        });
    </script>
@endsection
