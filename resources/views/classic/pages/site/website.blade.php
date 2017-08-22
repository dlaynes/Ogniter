@extends('classic.layouts.default')

@section('title')Ogniter - Tools - {{ $site->name }}@stop
@section('description')Recommended Website - {{ $site->description }}@stop

@section('head')
    <link rel="stylesheet" href="{{ $cdnHost }}css/jquery.noty.css" />
    <link rel="stylesheet" href="{{ $cdnHost }}css/noty_theme_default.css" />
@stop

@section('scripts')
    <script src="{{ $cdnHost }}js/jquery.raty.min.js"></script>
    <script src="{{ $cdnHost }}js/jquery.noty.js"></script>
    <script src="{{ $cdnHost }}js/mvc/routes/recommended.js"></script>
@stop


@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="site/recommended"><?php echo $lang->trans('ogniter.community_tools')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{{ $currentPath }}">{{ $site->name }}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> {{ $lang->trans('ogniter.tools').' - '.$site->name }}</h2>
            </div>
        </div>
        <div class="box above-me">
            <div class="box-content">
                <?php
                if($site->votes > 0 ){
                    $score = round($site->score / $site->votes);
                } else{
                    $score = 0;
                } ?>
                <table class="table table-striped table-condensed">
                    <tr>
                        <td>
                            @if($site->image)
                                <p><a href="{{ $site->url }}" target="_blank">
                                        <img width="300" src="{{ $cdnHost }}img/sites/{{ $site->image }}" alt="" title="" /></a></p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $site->description }}</td>
                    </tr>
                    <tr>
                        <td>URL:<br /><a href="{{ $site->url }}" target="_blank">{{ $site->url }}</a></td>
                    </tr>
                    <tr>
                        <td><div data-id="{{ $site->id }}" class="raty" data-score="{{ $score }}"></div></td>
                    </tr>
                    <tr>
                        <td>Votes: <span id="votes-{{ $site->id }}">{{ $site->votes }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
        @include('classic.partials.disqus')
    </div>

    <div class="span3">
        @include('classic.partials.shared.poll_form')
        @include('classic.partials.home.countrylist')
    </div>
@endsection