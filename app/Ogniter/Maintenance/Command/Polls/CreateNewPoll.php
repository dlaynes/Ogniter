<?php

namespace App\Ogniter\Maintenance\Command\Polls;

use Illuminate\Console\Command;
use DB;
use App\Ogniter\Model\Website\Poll;

class CreateNewPoll extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ogame:poll-new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans the update queue from errors';

    protected $signature = 'ogame:poll-new {question} {answers}';

    public function handle(Poll $pollModel)
    {
        $question = $this->argument('question');
        $answers = \explode(',', $this->argument('answers'));

        //Too lazy to math this
        $list = \range('a','z');
        if(count($answers) > 26){
            $this->error("Too many answers!!!!");
            return;
        }

        $pollModel->active = 0;
        $pollModel->question = $question;
        $pollModel->save();

        if(!$pollModel->id){
            $this->error("Could not create a new poll");
            return;
        }
        $pollModel->disablePolls();
        foreach($answers as $k => $answer){
            Poll::appendAnswer($pollModel->id,$list[$k], $answer);
        }
        $pollModel->active = 1;
        $pollModel->save();

        $this->info("New poll created");
    }
}