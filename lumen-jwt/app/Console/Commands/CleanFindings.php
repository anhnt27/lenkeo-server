<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Repositories\PlayerFindingTeamInterface;
use App\Repositories\TeamFindingMatchInterface;
use App\Repositories\TeamFindingPlayerInterface;


class CleanFindings extends Command
{
    const EXPIRED_DAYS = 12;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:findings';

    protected $teamFindingMatchs;
    protected $playerFindingTeams;
    protected $teamFindingPlayers;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up all expired finding news from players';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TeamFindingPlayerInterface $teamFindingPlayers, PlayerFindingTeamInterface $playerFindingTeams, TeamFindingMatchInterface $teamFindingMatchs)
    {
        $this->teamFindingMatchs = $teamFindingMatchs;
        $this->teamFindingPlayers = $teamFindingPlayers;
        $this->playerFindingTeams = $playerFindingTeams;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expiredDate      = Carbon::now()->subDays(self::EXPIRED_DAYS);
        $expiredMysqlDate = $expiredDate->format('Y-m-d');
        //
        $this->teamFindingMatchs->cleanUp($expiredMysqlDate);
        $this->teamFindingPlayers->cleanUp($expiredMysqlDate);
        $this->playerFindingTeams->cleanUp($expiredMysqlDate);
    }
}
