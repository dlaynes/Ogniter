@extends('classic.layouts.default')

@section('title')Ogniter - FAQ / Support @stop
@section('description')FAQ / Support / Tickets / Suggestions @stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li><a href="/">{{ $lang->trans('ogniter.og_home') }}</a><span class="divider">/</span></li>
        <li><a href="site/faq">{{ $lang->trans('ogniter.faq_support') }}</a></li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right icon-white"></i> {{ $lang->trans('ogniter.faq_support') }}</h2>
            </div>
        </div>
        @include('classic.partials.disqus')
    </div>

    <div class="span3">
        @include('classic.partials.shared.statistics')
        @include('classic.partials.home.countrylist')
    </div>
@endsection