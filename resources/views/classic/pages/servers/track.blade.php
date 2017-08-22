@extends('classic.layouts.default')

@section('title'){{ $PAGE_TITLE }}@endsection
@section('description'){{ $PAGE_DESCRIPTION }}@endsection

@section('head')

@endsection

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
                    {{ $currentUniverse->local_name.' - '.$title }}</h2>
            </div>
            <div class="box-content above-me">
                <?php
                $dif = time() - $last_update;
                $prev_galaxy = $galaxy-1;
                if($prev_galaxy < 1){ $prev_galaxy=$currentUniverse->galaxies; }

                $next_galaxy = $galaxy+1;
                if($next_galaxy > $currentUniverse->galaxies){ $next_galaxy = 1; }

                $system_limit = $currentUniverse->systems+1;

                $lang_position = $lang->trans('ogniter.og_location');

                if($currentUniverse->api_enabled){ ?>
                <div class="pull-left">
                    <?php echo $lang->trans('ogniter.last_update'),' (',$lang->trans('ogniter.planets'),'): ',$tagsHelper->parseTime($dif)?>
                </div>
                <div class="pull-right">
                    <?php echo $lang->trans('ogniter.next_update'),': ',$tagsHelper->parseTime(86400*7 - $dif, FALSE)?>
                </div>
                <p>&nbsp;</p>
                <hr />
                <?php } ?>
                <div>
                    {!! Form::open(['class'=>'form-inline', 'url'=> $uniShortCode.'/track/'.$mode.'/-/'.$param.'/'.$type]) !!}
                        <ul class="pager">
                            <li class="previous">
                                <a href="<?php echo $uniShortCode?>/track/<?php echo $mode,'/'.$prev_galaxy,'/',$param,'/', $type?>" class="galaxy-change">
                                    &larr; [<?php echo $lang->trans('ogniter.og_galaxy'),' ',$prev_galaxy?>]</a>
                            </li>
                            <li class="track-search">

                                <label for="galaxy"><?php echo $lang->trans('ogniter.og_galaxy')?>:</label>
                                <select class="input-mini" name="galaxy" id="galaxy">
                                    <?php
                                    $limit = $currentUniverse->galaxies+1;
                                    for($i=1; $i < $limit; $i++){ ?>
                                    <option value="<?php echo $i?>" <?php if($galaxy==$i){ echo 'selected="selected"';}?>><?php echo $i?></option>
                                    <?php } ?>
                                </select>
                                <?php if($mode=='player-status' || $mode=='alliance' || $mode=='compare-players' || $mode=='compare-alliances' ){ ?>
                                &nbsp; <label for="type"><?php echo $lang->trans('ogniter.og_search')?>:</label>
                                <select id="type" name="type">
                                    <option value="1"<?php if($type==1){ echo ' selected="selected"'; }?>><?php echo $lang->trans('ogniter.planets')?></option>
                                    <option value="2"<?php if($type==2){ echo ' selected="selected"'; }?>><?php echo $lang->trans('ogniter.moons')?></option>
                                </select>
                                <?php } else if($mode=='bandits-emperors'){ ?>
                                &nbsp; <label for="param"><?php echo $lang->trans('ogniter.og_type')?>:</label>
                                <select id="param" name="param">
                                    <option value="<?php echo \App\Ogniter\Model\Ogame\Player::BANDIT?>"
                                        <?php if($param==\App\Ogniter\Model\Ogame\Player::BANDIT){ echo ' selected="selected"'; }?>><?php echo $lang->trans('ogniter.bandit')?></option>
                                    <option value="<?php echo \App\Ogniter\Model\Ogame\Player::STAR_LORD?>"
                                    <?php if($param==\App\Ogniter\Model\Ogame\Player::STAR_LORD){ echo ' selected="selected"'; }?>><?php echo $lang->trans('ogniter.emperor')?></option>
                                </select>
                                <?php } else if($mode=='free-slots'){ ?>
                                <label for="param"><?php echo $lang->trans('ogniter.og_search')?>:</label>
                                <select id="param" name="param">
                                    <option value="0" <?php if($param=='0'){ echo 'selected="selected"';}?>><?php echo $lang->trans('ogniter.occupied_planets')?></option>
                                    <option value="1" <?php if($param=='1'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 1</option>
                                    <option value="2" <?php if($param=='2'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 2</option>
                                    <option value="3" <?php if($param=='3'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 3</option>
                                    <option value="4" <?php if($param=='4'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 4</option>
                                    <option value="5" <?php if($param=='5'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 5</option>
                                    <option value="6" <?php if($param=='6'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 6</option>
                                    <option value="7" <?php if($param=='7'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 7</option>
                                    <option value="8" <?php if($param=='8'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 8</option>
                                    <option value="9" <?php if($param=='9'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 9</option>
                                    <option value="10" <?php if($param=='10'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 10</option>
                                    <option value="11" <?php if($param=='11'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 11</option>
                                    <option value="12" <?php if($param=='12'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 12</option>
                                    <option value="13" <?php if($param=='13'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 13</option>
                                    <option value="14" <?php if($param=='14'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 14</option>
                                    <option value="15" <?php if($param=='15'){ echo 'selected="selected"';}?>><?php echo $lang_position?> 15</option>
                                </select>
                                <?php } ?>
                                <input type="text" name="name" value="" class="hide" />
                                <input type="submit" class="submit btn btn-warning" value="<?php echo $lang->trans('ogniter.og_send')?>" />
                            </li>
                            <li class="next">
                                <a href="<?php echo $uniShortCode,'/track/',$mode,'/',$next_galaxy,'/',$param,'/', $type?>" class="galaxy-change">
                                    [<?php echo $lang->trans('ogniter.og_galaxy'),' ',$next_galaxy?>] &rarr;</a>
                            </li>
                        </ul>

                        <?php if($mode=='player-status'){ ?>
                        <p class="track-search"> &nbsp; <?php echo $lang->trans('ogniter.og_player')?>: &nbsp;
                            <label class="checkbox chk-mini text-success" title="<?php echo $lang->trans('ogniter.normal')?>"><input type="checkbox" id="normal" name="filters[]" value="" <?php if(!$param || $param=='-'){ echo 'checked="checked"'; }?> /> <b><?php echo $lang->trans('ogniter.normal')?></b></label> &nbsp;
                            <label class="checkbox chk-mini text-success" title="<?php echo $lang->trans('ogniter.og_inactive')?>"><input type="checkbox" id="filter1" class="i" name="filters[]" value="i" <?php if(strpos( $param,'i')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_inactive')?></label> &nbsp;
                            <label class="checkbox chk-mini text-success" title="<?php echo $lang->trans('ogniter.og_inactive_30')?>"><input type="checkbox" id="filter2" class="i" name="filters[]" value="I" <?php if(strpos( $param,'I')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_inactive_30')?></label> &nbsp;
                            <label class="checkbox chk-mini text-success" title="<?php echo $lang->trans('ogniter.og_v_mode')?>"><input type="checkbox" id="filter3" name="filters[]" value="v" <?php if(strpos( $param,'v')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_v_mode')?></label> &nbsp;
                            <label class="checkbox chk-mini text-success" title="<?php echo $lang->trans('ogniter.og_suspended')?>"><input type="checkbox" id="filter4" name="filters[]" value="b" <?php if(strpos( $param,'b')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_suspended')?></label> &nbsp;
                            <label class="checkbox chk-mini text-success" title="<?php echo $lang->trans('ogniter.og_outlaw')?>"><input type="checkbox" id="filter5" name="filters[]" value="o" <?php if(strpos( $param,'o')!==FALSE){ echo 'checked="checked"'; }?> /> <?php echo $lang->trans('ogniter.og_outlaw')?></label> &nbsp;
                        </p>
                        <?php } ?>

                        <?php if($mode=='moons'){ ?>
                        <p class="pull-left"><strong><?php echo $lang->trans('ogniter.moons')?>:</strong></p>
                        <?php }elseif($mode=='player-status'){ ?>
                        <p class="pull-left"><strong><?php echo $lang->trans('ogniter.by_player_status')?>: <em>( <?php echo ($param && $param !='-')? $param : $lang->trans('ogniter.normal')?> )</em></strong></p>
                        <?php } elseif($mode!='free-slots'|| $mode=='free-slots' && !$param){ ?>
                        <p class="pull-left"><strong><?php echo $lang->trans('ogniter.occupied_planets')?>:</strong></p>
                        <?php } else{ ?>
                        <p class="pull-left"><strong><?php echo $lang_position ?>: <em><?php echo $param?></em></strong></p>
                        <?php } ?>

                        <?php if($mode=='free-slots'&&!$param){ ?>

                        <div class="pull-right">
                            <table class="table table-striped table-bordered table-condensed">
                                <tr><th><?php echo $lang->trans('ogniter.caption')?></th></tr>
                                <tr><td><span class="label label-success"><?php echo sprintf($lang->trans('ogniter.n_occupied_planets'), 0)?></span></td></tr>
                                <tr><td><span class="label label-info"><?php echo sprintf($lang->trans('ogniter.free_slots_range'), 1, 5)?></span></td></tr>
                                <tr><td><span class="label"><?php echo sprintf($lang->trans('ogniter.free_slots_range'), 6, 10)?></span></td></tr>
                                <tr><td><span class="label label-warning"><?php echo sprintf($lang->trans('ogniter.free_slots_range'), 11, 14)?></span></td></tr>
                                <tr><td><span class="label label-important"><?php echo sprintf($lang->trans('ogniter.n_occupied_planets'), 15)?></span></td></tr>
                            </table>
                        </div>
                        <?php }

                        $ENTITIES = array();
                        $e_colors = array('label-important','label-warning','label-success','label-info');
                        if($mode=='compare-players' || $mode=='compare-alliances'){

                        $e_pos = 0;
                        ?><div class="pull-right">
                            <table class="table table-striped table-bordered table-condensed">
                                <?php
                                foreach($objects as $object){
                                $object->pos = $e_pos;
                                $ENTITIES[$object->entity_id] = $object;
                                $name_ = ($mode =='compare-alliances' ? $object->name.' ['.$object->tag.']' : $object->name);
                                $url_ = ($mode =='compare-alliances' ? $uniShortCode.'/alliance/'.$object->entity_id : $uniShortCode.'/player/'.$object->entity_id); ?>
                                <tr><td><a class="label <?php echo $e_colors[$e_pos]?>" href="<?php echo e($url_)?>"><?php echo e($name_)?></a></td></tr>
                                <?php
                                $e_pos++;
                                } ?>
                            </table>
                        </div>
                        <?php } ?>

                        <div class="clearfix"></div>
                        <?php
                        if($type==1){ $pt = 'icon-globe'; }
                        else { $pt = 'icon-adjust'; }

                        if($mode=='compare-alliances' || $mode =='compare-players' ){
                        for($i=1; $i < $system_limit; $i++){
                        if(isset($galaxy_info[$i])){
                        $content_ = '';
                        foreach($galaxy_info[$i] as $entity){
                        $color_pos = $ENTITIES[$entity->entity_id]->pos;
                        $content_ .= '<span class="label '.$e_colors[$color_pos].'"><i class="icon-ok"></i></span><br />';
                        }
                        } else {
                        $content_ = '<span class="label"><i class="icon-remove"></i></span>';
                        } ?>
                        <div class="pull-left system" style="height: 70px; border: 1px solid #ccc" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$i?>">[<?php echo $galaxy,':', $i?>]</a><br />
                                <?php echo $content_?>
                            </p>
                        </div> <?php
                        }

                        } elseif($mode=='free-slots'){
                        if(!$param){
                        for($i=1; $i < $system_limit; $i++){

                        if(isset($galaxy_info[$i])){
                        $system = $galaxy_info[$i];
                        if($system->count == 15){
                        $class = ' label-important';
                        } elseif($system->count> 10){
                        $class = ' label-warning';
                        } elseif($system->count> 5){
                        $class = '';
                        } else {
                        $class = ' label-info';
                        }
                        } else{
                        $system = (object) array('system'=>$i,'count'=>0);
                        $class = ' label-success';
                        } ?>
                        <div class="pull-left system" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$system->system?>">[<?php echo $galaxy,':', $system->system?>]</a><br />
                                <span class="label<?php echo $class?>"><?php echo $system->count?>/15</span>
                            </p>
                        </div>
                        <?php
                        }
                        } else{
                        for($i=1; $i < $system_limit; $i++){
                        if(isset($galaxy_info[$i])){ ?>
                        <div class="pull-left system" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$i?>">[<?php echo $galaxy,':', $i?>]</a><br />
                                <span class="label"> <i class="icon-remove"></i> </span>
                            </p>
                        </div>
                        <?php } else{ ?>
                        <div class="pull-left system" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$i?>">[<?php echo $galaxy,':', $i?>]</a><br />
                                <span class="label label-success"> <i class="icon-ok"></i> </span>
                            </p>
                        </div>
                        <?php }
                        }
                        }
                        } else if($mode=='player-status'){
                        for($i=1; $i < $system_limit; $i++){
                        if(isset($galaxy_info[$i])){
                        $system = $galaxy_info[$i];
                        $class = ' label-success';
                        } else {
                        $class = '';
                        $system = (object) array('system'=>$i,'count'=>0);
                        } ?>
                        <div class="pull-left system" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$system->system?>">[<?php echo $galaxy,':', $system->system?>]</a><br />
                                <span class="label<?php echo $class?>"><?php echo $system->count?> <i class="<?php echo $pt?>"></i></span>
                            </p>
                        </div>
                        <?php
                        }
                        }  else if($mode=='alliance') {
                        for($i=1; $i < $system_limit; $i++){
                        if(isset($galaxy_info[$i])){
                        $system = $galaxy_info[$i];
                        $class = ' label-important';
                        } else {
                        $class = '';
                        $system = (object) array('system'=>$i,'count'=>0);
                        } ?>
                        <div class="pull-left system" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$system->system,'#alliance', $alliance->alliance_id?>">[<?php echo $galaxy,':', $system->system?>]</a><br />
                                <span class="label<?php echo $class?>"><?php echo $system->count?> <i class="<?php echo $pt?>"></i></span>
                            </p>
                        </div>
                        <?php
                        }
                        } else if($mode=='bandits-emperors'){
                        for($i=1; $i < $system_limit; $i++){
                        if(isset($galaxy_info[$i])){
                        $system = $galaxy_info[$i];
                        $class = ' label-info';
                        } else {
                        $class = '';
                        $system = (object) array('system'=>$i,'count'=>0);
                        }
                        if($param==\App\Ogniter\Model\Ogame\Player::STAR_LORD){
                        $icon = 'icon-star-empty';
                        } else{
                        $icon = 'icon-star';
                        }
                        ?>
                        <div class="pull-left system" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$system->system?>">[<?php echo $galaxy,':', $system->system?>]</a><br />
                                <span class="label<?php echo $class?>"><?php echo $system->count?> <i class="<?php echo $icon?>"></i></span>
                            </p>
                        </div>
                        <?php
                        }
                        } else if($mode=='moons'){
                        for($i=1; $i < $system_limit; $i++){
                        if(isset($galaxy_info[$i])){
                        $system = $galaxy_info[$i];
                        $class = ' label-info';
                        } else {
                        $class = '';
                        $system = (object) array('system'=>$i,'count'=>0);
                        }

                        $icon = 'icon-adjust';
                        ?>
                        <div class="pull-left system" id="system<?php echo $i?>">
                            <p>
                                <a href="<?php echo $uniShortCode,'/galaxy/',$galaxy,'/',$system->system?>">[<?php echo $galaxy,':', $system->system?>]</a><br />
                                <span class="label<?php echo $class?>"><?php echo $system->count?> <i class="<?php echo $icon?>"></i></span>
                            </p>
                        </div>
                        <?php
                        }
                        } ?>
                    {!! Form::close() !!}
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <?php if($mode=='player-status'){ ?>
    <script>
        jQuery(document).ready(function(){
            var $checkboxes = jQuery('.chk-mini input'), $i = jQuery('.i');

            $checkboxes.change(function(){
                var $chk = jQuery(this);
                if($chk.attr('id')==='normal'){
                    if($chk.prop('checked')){
                        $checkboxes.not($chk).prop('checked', false);
                    }
                } else{
                    if($chk.hasClass('i') && $chk.prop('checked')  ){
                        $i.not($chk).prop('checked', false);
                    }
                    jQuery('#normal').prop('checked', false);
                }
                jQuery.uniform.update("input:checkbox, input:radio");
            }).find(0).trigger('change');
        });
    </script>
    <?php } ?>
@endsection