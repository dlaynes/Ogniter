@extends('classic.layouts.default')

@section('title') Not Found @endsection
@section('description') Resource not Found @endsection

@section('content')
    <div class="span8">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right icon-white"></i> Error</h2>
            </div>
            <div class="box-content clearfix">
                <p class="text-error">{{ $lang->trans('ogniter.not_found_try_again') }}</p><br />
                <table class="table">
                    <tr>
                        <td> <a href="{{$uniShortCode}}/search-form" class="btn btn-mini btn-success" title="{{$lang->trans('ogniter.og_search')}}"><i class="icon-search"></i> {{$lang->trans('ogniter.og_search')}}</a> </td>
                        <td> <a href="{{$uniShortCode}}/galaxy" class="btn btn-mini btn-danger" title="{{$lang->trans('ogniter.og_galaxy_view')}}"><i class="icon-globe"></i> {{$lang->trans('ogniter.og_galaxy_view')}}</a></td>
                        <td> <a href="{{$uniShortCode}}/highscore/players/0" class="btn btn-mini btn-primary" title="{{$lang->trans('ogniter.og_ranking')}}"><i class="icon-list-alt"></i> {{$lang->trans('ogniter.og_ranking')}}</a></td>
                    </tr>
                </table>

            </div>
        </div>
        <iframe src="//notfound-static.fwebservices.be/404/index.html?&amp;key=f8d096d01c09033b12a3d1e18acbc42f"
                width="100%" height="650" frameborder="0"></iframe>
    </div>
    <div class="span4">
        @include('classic.partials.twitter')
        @include('classic.partials.home.countrylist')
    </div>
@endsection