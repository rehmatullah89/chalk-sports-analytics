<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use DB;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateLeaderBoard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaderboard:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update points and prediction percentages.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::with('roles')->whereHas("roles", function($q) {
            $q->whereIn("name", ["user"]);
        })->join('user_game_prediction', 'user_game_prediction.user_id', '=', 'users.id')
            ->OrderBy('users.id')->pluck('users.name','users.id')->toArray();

        $teams = Team::pluck('name','id')->toArray();

        if(count($users)>0)
            DB::table('leaderboard')->where('leaderboard_id','>',0)->delete();

        foreach ($users as $userId => $userName)
        {
            $percentile = 0;
            $totalWrongWins = 0;
            $totalCorrectWins = 0;
            $totalPredictions = 0;
            $totalCorrectPoints = 0;
            $totalWrongPoints = 0;
            $totalCorrectPredictions = 0;
            $user_prediction_data = DB::table('user_game_prediction as ugp')
                ->join('games as g', 'g.game_id', '=', 'ugp.game_id')
                ->where('ugp.user_id', $userId)->where('g.team_1_score','<>',0)->get();

            foreach ($user_prediction_data as $data){
                //number of wins predicted
                $actual_winner_id = ($data->team_1_score > $data->team_2_score? $data->team_1_id:$data->team_2_id);
                if($data->winner == $teams[$actual_winner_id]){
                    $totalCorrectWins ++;
                    $totalPredictions ++;
                    $totalCorrectPredictions ++;
                }elseif(!is_null($data->winner)){
                    $totalWrongWins ++;
                    $totalPredictions ++;
                }

                //number of spread elements predicted
//                if($data->spread != 0 && ($data->spread == $data->t1_spread || $data->spread == $data->t2_spread)){
                if($data->spread != 0 && ($data->spread == $actual_winner_id)){
                    $totalCorrectPoints ++;
                    $totalPredictions ++;
                    $totalCorrectPredictions ++;
                }elseif(!is_null($data->spread) && $data->spread != 0){
                    $totalWrongPoints ++;
                    $totalPredictions ++;
                }

                //number of overunder elements predicted
                //if($data->over_under != 0 && (($data->over_under < 0 && ($data->over_under <= $data->t1_over_under)) || ($data->over_under > 0 && ($data->over_under >= $data->t2_over_under)))){
                if($data->over_under != 0 && $data->over_under == $actual_winner_id){
                    $totalCorrectPoints ++;
                    $totalPredictions ++;
                    $totalCorrectPredictions ++;
                }elseif(!is_null($data->over_under) && $data->over_under != 0){
                    $totalWrongPoints ++;
                    $totalPredictions ++;
                }
            }

            $overallPercentile = $this->getPredictionPercentile($totalCorrectPredictions);
            /*if($totalPredictions > 0)
                $overallPercentile = number_format(@($totalCorrectPredictions / $totalPredictions)*100, 2);*/

            if($totalCorrectPredictions > 0) {
                DB::table('leaderboard')
                    ->updateOrInsert(
                        ['user_id' => $userId],
                        ['username' => $userName, 'points' => $totalCorrectPredictions, 'percent_win_correct' => $totalCorrectWins, 'overall_percentile' => $overallPercentile]
                    );
            }
        }

        return 0;
    }

    function getPredictionPercentile($userCorrectPredictions)
    {
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');

        $users = User::with('roles')->whereHas("roles", function($q) {
            $q->whereIn("name", ["user"]);
        })->join('user_game_prediction', 'user_game_prediction.user_id', '=', 'users.id')
            ->OrderBy('users.id')->pluck('users.name','users.id')->toArray();

        $teams = Team::pluck('name','id')->toArray();

        $allUsersData = [];
        foreach ($users as $userId => $userName) {
            $totalCorrectPredictions = 0;
            $user_prediction_data = DB::table('user_game_prediction as ugp')
                ->join('games as g', 'g.game_id', '=', 'ugp.game_id')
                ->where('ugp.user_id', $userId)->get();

            foreach ($user_prediction_data as $data) {
                //number of wins predicted
                $actual_winner_id = ($data->team_1_score > $data->team_2_score ? $data->team_1_id : $data->team_2_id);
                if ($data->winner == $teams[$actual_winner_id]) {
                    $totalCorrectPredictions++;
                }
                if ($data->spread != 0 && ($data->spread == $actual_winner_id)) {
                    $totalCorrectPredictions++;
                }
                if ($data->over_under != 0 && $data->over_under == $actual_winner_id) {
                    $totalCorrectPredictions++;
                }
            }
            array_push($allUsersData, $totalCorrectPredictions);
        }

        $min = min($allUsersData);
        $max = max($allUsersData);
        $range = ($max - $min)/100;
        $range = ($range > 0)?$range:1;

        $percentileRank = abs(number_format((($userCorrectPredictions - $min)/$range) - 1));


        return $percentileRank;
    }
}
