@extends('classic.layouts.default')

@section('title')Ogniter - Poll list @stop
@section('description')Latest polls published on the site @stop

@section('breadcrumb')
    <ul class="breadcrumb">
        <li>
            <a href="/"><?php echo $lang->trans('ogniter.og_home')?></a> <span class="divider">/</span>
        </li>
        <li>
            <a href="site/polls">Polls</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="span9">
        <div class="box">
            <div class="box-header well">
                <h2><i class="icon-chevron-right"></i> Polls</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-condensed">
                    @foreach($polls as $poll)
                        <tr><td>#{{ $poll->id  }}</td>
                            <td> <a href="site/poll/{{ $poll->id  }}">{{ $poll->question }}</a></td></tr>
                    @endforeach
                </table>
            </div>
        </div>
        @include('classic.partials.disqus')
    </div>

    <div class="span3">
        @include('classic.partials.shared.statistics')
        @include('classic.partials.twitter')
    </div>
@endsection