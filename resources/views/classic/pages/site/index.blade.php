@extends('classic.layouts.default')

@section('title'){{ $lang->trans('ogniter.og_site_title') }}@stop
@section('description'){{ $lang->trans('ogniter.og_description') }}@stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/">{{ $lang->trans('ogniter.og_home') }}</a><span class="divider">/</span></li>
    </ul>
@endsection

@section('content')
    <div class="span8">
        <div class="box">
            <div class="box-content clearfix center">
                <div class="">
                    <img src="{{ $cdnHost }}img/home-ogniter.jpg" alt="Ogniter" title="Ogniter" />
                </div>
                <h1 class="above-me">{!! $lang->trans('ogniter.og_home_title') !!}</h1>
                <p><{!! $lang->trans('ogniter.og_home_description') !!}</p>
                <br />
                <p>{!! $lang->trans('ogniter.og_home_how_to_start') !!}</p>
            </div>
        </div>
        <div class="box above-me">
            <div class="box-header well">
                <h2><i class="icon-th"></i> {{ $lang->trans('ogniter.og_home_domain_list')}}</h2>
            </div>
            <div class="box-content important clearfix">
                <?php $servers_l = $lang->trans('ogniter.og_servers'); ?>
                @foreach($countries as $dom)
                    <span class="server-item server-{{ $dom->language }}">
                        <a href="{{ $dom->language }}">
                            <i class="flag flag-{{ $dom->flag }}"></i> {{ $dom->domain }}</a>
                        <br />{{ $dom->num_servers }} {{ $servers_l }}
                    </span>
                @endforeach
            </div>
        </div>
        @include('classic.partials.home.worldtop100')
    </div>
    <div class="span4">
        @include('classic.partials.twitter')
        @include('classic.partials.shared.statistics')
    </div>
@endsection