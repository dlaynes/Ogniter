<div class="box">
    <div class="box-header well">
        <h3><i class="icon-list"></i> {{ $lang->trans('ogniter.vote') }}</h3>
    </div>
    <div class="box-content">
        {!! Form::open(array('url' => 'site/poll/'.$currentPoll->id, 'class'=>"no-margin-poll")) !!}
            <h5>{{ '#'.$currentPoll->id.' - '.$currentPoll->question }}</h5>
            <br />
            <table class="table table-striped table-condensed">
                @foreach($currentPoll->answers as $answer )
                <tr><td><input type="radio" name="answer" id="answer-{{ $answer->value }}"
                               value="{{ $answer->value }}" data-no-uniform="true" />&nbsp;&nbsp; {{ $answer->answer }}</td></tr>
                @endforeach
            </table><br />
            <div class="center">
                <input type="submit" class="btn btn-primary" value="<?php echo $lang->trans('ogniter.og_send')?>" />
                &nbsp;
                <a href="site/poll/{{ $currentPoll->id }}"><?php echo $lang->trans('ogniter.view_results')?></a>
            </div>
        {!! Form::close() !!}
    </div>
</div>