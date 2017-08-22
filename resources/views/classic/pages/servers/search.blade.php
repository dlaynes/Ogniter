<?php
$a = array();
$a['PAGE_TITLE'] = $lang->trans('ogniter.title_server_index');
$a['PAGE_DESCRIPTION'] = $lang->trans('ogniter.description_server_index');

$a = str_replace(array('%server%','%domain%'), array($currentUniverse->local_name, $currentCountry->domain), $a);
?>
@extends('classic.layouts.default')
@section('title'){{ $a['PAGE_TITLE'] }}@endsection
@section('description'){{ $a['PAGE_DESCRIPTION'] }}@endsection

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
            <a href="{{ $uniShortCode }}">{{ $currentUniverse->local_name }}</a> <span class="divider"></span>
        </li>
        <li>
            <a href="{{ $currentPath }}">{{ $lang->trans('ogniter.og_search') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $lang->trans('ogniter.og_search').' - '.$currentUniverse->local_name }}</h2>
            </div>
            <div class="box-content above-me">
                {!! Form::open(array('url' => $uniShortCode.'/search', 'class'=>"form-inline")) !!}
                    <div class="clearfix center">
                        <label for="galaxy"><?php echo $lang->trans('ogniter.og_search_by')?>:</label>
                        <select class="input-medium" name="search_by" id="search_by">
                            <option value="player" <?php if($search_by=='player'){ echo 'selected="selected"';}?>><?php echo $lang->trans('ogniter.og_player')?></option>
                            <option value="planet" <?php if($search_by=='planet'){ echo 'selected="selected"';}?>><?php echo $lang->trans('ogniter.og_planet')?></option>
                            <option value="tag" <?php if($search_by=='tag'){ echo 'selected="selected"';}?>><?php echo $lang->trans('ogniter.og_alliance_tag_long')?></option>
                            <option value="alliance" <?php if($search_by=='alliance'){ echo 'selected="selected"';}?>><?php echo $lang->trans('ogniter.og_alliance')?></option>
                        </select>
                        <input type="text" name="name_hddn" value="" class="hide" style="display:none" />
                        <label for="search"><?php echo $lang->trans('ogniter.og_text')?>:</label>
                        <input type="text" class="input-medium" name="search" id="search"
                               value="<?php echo e($search)?>" />
                        &nbsp;
                        <input type="submit" class="submit btn btn-primary" value="<?php echo $lang->trans('ogniter.og_send')?>" />
                        <br />
                        <div class="dnone" id="status_block">&nbsp;<br />
                            <label class="checkbox inline"><input type="checkbox" class="i" name="filters[]" value="i" <?php if(strpos( $filters,'i')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_inactive')?> (i)</label>
                            <label class="checkbox inline"><input type="checkbox" class="i" name="filters[]" value="I" <?php if(strpos( $filters,'I')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_inactive_30')?> (I)</label>
                            <label class="checkbox inline"><input type="checkbox" name="filters[]" value="v" <?php if(strpos( $filters,'v')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_v_mode')?> (v)</label>
                            <label class="checkbox inline"><input type="checkbox" name="filters[]" value="b" <?php if(strpos( $filters,'b')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_suspended')?> (b)</label>
                            <label class="checkbox inline"><input type="checkbox" name="filters[]" value="o" <?php if(strpos( $filters,'o')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_outlaw')?> (o)</label>
                            <br />
                            <label class="checkbox inline">
                                <input type="checkbox" name="strict_search" value="v" <?php if($strict_search){ echo 'checked="checked"'; }?> /> <strong>Strict Search?</strong>
                            </label>
                        </div>
                    </div>
                    <br />
                    <div class="search-container">
                        <?php
                        if($searchString && $searchString!='-'){
                            if($search_by=='tag'){ $search_by='alliance'; } ?>
                            @include('classic.partials.servers.search_'.$search_by,['search_results'=>$search_results, 'tagsHelper'=>$tagsHelper])
                            <?php
                            if($search_count){
                                echo $pager->render();
                            }
                        } else{
                            echo '<hr />'.e($lang->trans('ogniter.og_please_do_a_db_search'));
                        } ?>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="span3">
        @include('classic.partials.servers.popular_searches')
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