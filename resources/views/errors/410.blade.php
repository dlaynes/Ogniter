@extends('classic.layouts.default')

@section('title') Resource Not Available @endsection
@section('description') This resource is no longer available @endsection

@section('content')
    <div class="span9">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right icon-white"></i> Error</h2>
            </div>
            <div class="box-content clearfix">
                <p class='alert'>Sorry, this Ogame universe is no longer available in Ogniter</p>
                <p><a href='/' class='btn btn-primary'>{{ $lang->trans('ogniter.og_home') }}</a></p></div>
        </div>
        <iframe src="//notfound-static.fwebservices.be/404/index.html?&amp;key=f8d096d01c09033b12a3d1e18acbc42f"
                width="100%" height="650" frameborder="0"></iframe>
    </div>
    <div class="span3">
        @include('classic.partials.twitter')
        @include('classic.partials.home.countrylist')
    </div>
@endsection