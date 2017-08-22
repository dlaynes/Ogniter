@extends('classic.layouts.default')

@section('title')Ogniter - Privacy Policy@stop
@section('description')Privacy Policy@stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="privacy-policy">Privacy Policy</a>
        </li>
    </ul>
@endsection

@section('content')
<div class="span12">
    <div class="box">
        <div class="box-header well">
            <h2><i class="icon-chevron-right icon-white"></i> Ogniter Privacy Policy</h2>
        </div>
    </div>
    <div class="box above-me">        
        Todo: conseguir un privacy policy
    </div>
</div>
@endsection