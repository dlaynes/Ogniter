<?php

namespace App\Console;

use App\Ogniter\Api\Remote\Ogame\Command\AllianceQueue;
use App\Ogniter\Api\Remote\Ogame\Command\GalaxyQueue;
use App\Ogniter\Api\Remote\Ogame\Command\HighscoreQueue;
use App\Ogniter\Api\Remote\Ogame\Command\InstallUniverseQueue;
use App\Ogniter\Api\Remote\Ogame\Command\PlayerQueue;
use App\Ogniter\Api\Remote\Ogame\Command\UniverseQueue;
use App\Ogniter\Api\Remote\Ogame\Command\UpdateApiUniverses;
use App\Ogniter\Api\Remote\Ogame\Command\UpdateCommunities;
use App\Ogniter\Maintenance\Command\CleanUpdateErrors;
use App\Ogniter\Maintenance\Command\DailyStats;
use App\Ogniter\Maintenance\Command\PatchUniverses;
use App\Ogniter\Maintenance\Command\Polls\CreateNewPoll;
use App\Ogniter\Maintenance\Command\Polls\SwitchToPoll;
use App\Ogniter\Maintenance\Command\ToggleUniverse;
use App\Ogniter\Maintenance\Command\Websites\AddNewWebsite;

use App\Ogniter\Maintenance\Command\Restore\LoadCommunities;
use App\Ogniter\Maintenance\Command\Restore\LoadUniverses;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,

        UniverseQueue::class,
        AllianceQueue::class,
        PlayerQueue::class,
        GalaxyQueue::class,
        HighscoreQueue::class,
        DailyStats::class,
        CleanUpdateErrors::class,
        PatchUniverses::class,
        ToggleUniverse::class,
        UpdateApiUniverses::class,
        InstallUniverseQueue::class,
        SwitchToPoll::class,
        CreateNewPoll::class,
        AddNewWebsite::class,
        UpdateCommunities::class,

        LoadCommunities::class,
        LoadUniverses::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('ogame:alliance-queue')->cron('*/2 * * * *')
            ->withoutOverlapping()
            ->sendOutputTo(\storage_path('logs/alliances.log'));
        $schedule->command('ogame:player-queue')->cron('*/2 * * * *')
            ->withoutOverlapping()
            ->sendOutputTo(\storage_path('logs/players.log'));
        $schedule->command('ogame:galaxy-queue')->cron('*/2 * * * *')
            ->withoutOverlapping()
            ->sendOutputTo(\storage_path('logs/planets.log'));
        $schedule->command('ogame:universe-queue')->cron('*/2 * * * *')
            ->sendOutputTo(\storage_path('logs/universes.log'));
        $schedule->command('ogame:highscore-queue')->cron('*/2 * * * *')
            ->sendOutputTo(\storage_path('logs/highscore.log'));
        $schedule->command('ogame:daily-stats')->daily()
            ->sendOutputTo(\storage_path('logs/dailystats.log'));
        $schedule->command('ogame:clean-update-errors')->cron('0 * * * *')
            ->sendOutputTo(\storage_path('logs/updaterrors.log'));
        $schedule->command('ogame:install-universe-queue')->cron('*/4 * * * *')
            ->withoutOverlapping()
            ->sendOutputTo(\storage_path('logs/install-queue.log'));

        //how to install new universes:
        //Update the server name list configuration: ogame_servers.php (New France universe name)
        //php artisan ogame:update-api-universes
        //php artisan ogame:toggle-universe 597 (y 390) //Solo si te da flojera moverlos de lugar
    }
}
