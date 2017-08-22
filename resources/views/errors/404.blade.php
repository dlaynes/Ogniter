@extends('classic.layouts.default')

@section('title') Not Found @endsection
@section('description') Page not Found @endsection

@section('content')
    <div class="span9">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right icon-white"></i> Error</h2>
            </div>
            <div class="box-content clearfix">
                Page not found </div>
        </div>
        <iframe src="//notfound-static.fwebservices.be/404/index.html?&amp;key=f8d096d01c09033b12a3d1e18acbc42f"
                width="100%" height="650" frameborder="0"></iframe>
    </div>
    <div class="span3">
        @include('classic.partials.twitter')
        @include('classic.partials.home.countrylist')
    </div>
@endsection