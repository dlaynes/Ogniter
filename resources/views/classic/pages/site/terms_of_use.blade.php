@extends('classic.layouts.default')

@section('title')Ogniter - Terms of use@stop
@section('description')Terms of use@stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="terms-of-use">Terms of Use</a>
        </li>
    </ul>
@stop

@section('content')
<div class="span12">
    <div class="box">
        <div class="box-header well">
            <h2><i class="icon-chevron-right icon-white"></i> Terms of Use</h2>
        </div>
    </div>
    <div class="box above-me">
        Content
    </div>
</div>
@endsection