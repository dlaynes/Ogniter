@extends('classic.layouts.default')
@section('title'){{ $PAGE_TITLE }}@endsection
@section('description'){{ $PAGE_DESCRIPTION }}@endsection

@section('head')
    <link rel="stylesheet" href="{{ $cdnHost }}css/jquery-ui-1.8.21.custom.css" />
    <link rel="stylesheet" href="{{ $cdnHost }}js/tag-it/css/jquery.tagit.css" />
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
            <a href="{{ $uniShortCode }}">{{ $currentUniverse->local_name }}</a>  <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $uniShortCode }}/comparison">{{ $lang->trans('ogniter.og_comparison') }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        @include('classic.partials.servers.nav')
        <div class="box below-tab">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $lang->trans('ogniter.og_comparison').' - '.$currentUniverse->local_name }}</h2>
            </div>
            <div class="box-content above-me">

                <div class="row-fluid">
                    <div class="span6 well">
                        {!! Form::open(array('url' => $currentPath, 'class'=>"form-inline")) !!}
                            <p><i class="icon-align-right"></i> <?php echo $lang->trans('ogniter.compare_statistics')?></p>
                            <hr />
                            <section class="control-group">
                                <label for="by_player"><i class="icon-user"></i> <?php echo $lang->trans('ogniter.search_players')?> (Max: <?php echo $limit_comparison?>)</label>
                                <div class="">
                                    <input type="text" class="" id="by_player" name="by_player" />
                                    <br />
                                    <input type="submit" name="search_by_player" class="btn btn-primary" value="<?php echo $lang->trans('ogniter.og_search')?>" />
                                </div>
                            </section>
                            <hr />
                            <section class="control-group">
                                <label for="by_alliance"><i class="icon-screenshot"></i> <?php echo $lang->trans('ogniter.search_alliances')?> (Max: <?php echo $limit_comparison?>)</label>
                                <div class="">
                                    <input type="text" class="" id="by_alliance" name="by_alliance" />
                                    <br />
                                    <input type="text" name="name_hddn" value="" class="hide" style="display:none" />
                                    <input type="submit" name="search_by_alliance" class="btn btn-primary" value="<?php echo $lang->trans('ogniter.og_search')?>" />
                                </div>
                            </section>
                        {!! Form::close() !!}
                    </div>
                    <div class="span6 well">
                        {!! Form::open(array('url' => $currentPath, 'class'=>"form-inline")) !!}
                            <p><i class="icon-globe"></i> <?php echo $lang->trans('ogniter.find_planets')?></p>
                            <hr />
                            <section class="control-group">
                                <label for="by_player"><i class="icon-user"></i> <?php echo $lang->trans('ogniter.search_players')?> (Max: <?php echo $limit?>)</label>
                                <div class="">
                                    <input type="text" class="" id="planet_by_player" name="planet_by_player" />
                                    <br />
                                    <input type="submit" name="planet_search_by_player" class="btn btn-primary" value="<?php echo $lang->trans('ogniter.og_search')?>" />
                                </div>
                            </section>
                            <hr />
                            <section class="control-group">
                                <label for="by_alliance"><i class="icon-screenshot"></i> <?php echo $lang->trans('ogniter.search_alliances')?> (Max: <?php echo $limit?>)</label>
                                <div class="">
                                    <input type="text" class="" id="planet_by_alliance" name="planet_by_alliance" />
                                    <br />
                                    <input type="text" name="name_hddn" value="" class="hide" style="display:none" />
                                    <input type="submit" name="planet_search_by_alliance" class="btn btn-primary" value="<?php echo $lang->trans('ogniter.og_search')?>" />
                                </div>
                            </section>
                        {!! Form::close() !!}
                    </div>
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
    <script src="{{ $cdnHost }}js/jquery-ui-1.8.21.custom.min.js"></script>
    <script src="{{ $cdnHost }}js/tag-it/js/tag-it.min.js"></script>

    <script>
        jQuery(document).ready(function(){
            jQuery('#by_player').tagit(
                    {tagSource: function(search, showChoices){
                        var that = this;
                        if(search.term && search.term.length > 2 ){
                            $.ajax({
                                url: Ogniter.BASE_URL + "<?php echo $uniShortCode,'/autocomplete_tags'?>/1",
                                type: 'POST',
                                data: {search: search.term},
                                success: function(data) {
                                    var json = jQuery.parseJSON(data.replace('while(1);',''));

                                    showChoices(that._subtractArray(json.choices, that.assignedTags()));
                                }
                            });
                        }
                    },
                        allowSpaces: true,
                        singleField: true
                    });
            jQuery('#by_alliance').tagit(
                    {tagSource: function(search, showChoices){
                        var that = this;
                        if(search.term && search.term.length > 2 ){
                            $.ajax({
                                url: Ogniter.BASE_URL + "<?php echo $uniShortCode,'/autocomplete_tags'?>/2",
                                type: 'POST',
                                data: {search: search.term},
                                success: function(data) {
                                    var json = jQuery.parseJSON(data.replace('while(1);',''));

                                    showChoices(that._subtractArray(json.choices, that.assignedTags()));
                                }
                            });
                        }
                    },
                        allowSpaces: true,
                        singleField: true
                    });
            jQuery('#planet_by_player').tagit(
                    {tagSource: function(search, showChoices){
                        var that = this;
                        if(search.term && search.term.length > 2 ){
                            $.ajax({
                                url: Ogniter.BASE_URL + "<?php echo $uniShortCode,'/autocomplete_tags'?>/1",
                                type: 'POST',
                                data: {search: search.term},
                                success: function(data) {
                                    var json = jQuery.parseJSON(data.replace('while(1);',''));

                                    showChoices(that._subtractArray(json.choices, that.assignedTags()));
                                }
                            });
                        }
                    },
                        allowSpaces: true,
                        singleField: true,
                        tagLimit: 6,
                        onTagLimitExceeded: function(event, ui){

                        }
                    });
            jQuery('#planet_by_alliance').tagit(
                    {tagSource: function(search, showChoices){
                        var that = this;
                        if(search.term && search.term.length > 2 ){
                            $.ajax({
                                url: Ogniter.BASE_URL + "<?php echo $uniShortCode,'/autocomplete_tags'?>/2",
                                type: 'POST',
                                data: {search: search.term},
                                success: function(data) {
                                    var json = jQuery.parseJSON(data.replace('while(1);',''));

                                    showChoices(that._subtractArray(json.choices, that.assignedTags()));
                                }
                            });
                        }
                    },
                        allowSpaces: true,
                        singleField: true,
                        tagLimit: 3,
                        onTagLimitExceeded: function(event, ui){

                        }
                    });
        });
    </script>
@endsection