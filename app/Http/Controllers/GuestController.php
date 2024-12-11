<?php

namespace App\Http\Controllers;

use App\Models\Season;
use App\Models\User;
use App\Models\UserGame;
use DB;
use App\Models\Game;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDashboard(Request $request)
    {
        $teams = Team::pluck('name', 'id')->toArray();
        $logos = Team::pluck('logo', 'id')->toArray();
        $packages = Package::where('status','Active')->orderBy('order')->orderBy('subscription_price')->get();
        $games = Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->OrderBy('game_date')->get();

        $freeGames = Game::where('free_game', 'Y')->OrderBy('game_date')->take(2)->get();
        $userGames = Game::where('game_date', "<", Carbon::now()->format('Y-m-d'))->take(10)->get();
        $pastGames = Game::where('game_date', "<", Carbon::now()->format('Y-m-d'))->offset(10)->take(20)->get();

        return view('guests.dashboard', ['games' => $games, 'teams' => $teams, 'logos' => $logos, 'packages' => $packages, 'free_games'=>$freeGames, 'user_games'=>$userGames, 'past_games'=>$pastGames]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSchedules($seasonId=0, $weekNo=0)
    {
        $market_ats = [];
        if($seasonId == 0 || $weekNo == 0){
            $seasonId = Season::orderByDesc('id')->value('id');
            $weekNo = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
            $weekNo = ($weekNo==0?1:$weekNo);
        }

        $teams = Team::pluck('name','id')->toArray();
        $logos = Team::pluck('logo','id')->toArray();
        $seasons = Season::pluck('name', 'id')->toArray();
        $weeks = getGameWeeks();
        $games = Game::OrderBy('game_id')->whereWeekNumber($weekNo)->whereSeasonId($seasonId)->get();
        $market_values = DB::table('nfl_additional_team_odds')->whereSeasonId($seasonId)->get();
        foreach ($market_values as $values){
            $market_ats[$values->week_number][$values->away_team_id][$values->home_team_id] = ['over_under'=>$values->overunder,'spread'=>$values->spread,'home_money_line'=>$values->home_team_money_line,'away_money_line'=>$values->away_team_money_line,'home_team_ats'=>$values->home_team_ats,'away_team_ats'=>$values->away_team_ats];
        }

        $dataList = [];
        foreach ($games as $key => $object){
            $dataList[$key]['game_id'] = $object->game_id;
            $dataList[$key]['game_date'] = date("l, F d", strtotime($object->game_date));
            $dataList[$key]['team_1_id'] = $object->team_1_id;
            $dataList[$key]['team_2_id'] = $object->team_2_id;
            $dataList[$key]['team_1_score'] = $object->team_1_score;
            $dataList[$key]['team_2_score'] = $object->team_2_score;
            $dataList[$key]['team_1'] = $teams[$object->team_1_id];
            $dataList[$key]['team_2'] = $teams[$object->team_2_id];
            $dataList[$key]['week_number'] = $object->week_number;

            $dataList[$key]['away_ats'] = '';
            $dataList[$key]['home_ats'] = '';
            if(isset($market_ats[$object->week_number][$object->team_2_id][$object->team_1_id])){
                $dataList[$key]['away_ats'] = '<br/><span style="font-size: 12px; min-width: 40px;">'.((@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_money_line'] < @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread']):addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread'])).'|'.(addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_money_line'])).'|'.(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['over_under']).'&nbsp; ATS &nbsp;'.rtrim(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_team_ats'], '-0').'</span>';
                $dataList[$key]['home_ats'] = '<br/><span style="font-size: 12px; min-width: 40px;">'.((@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_money_line'] > @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread']):addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread'])).'|'.(addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_money_line'])).'|'.(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['over_under']).'&nbsp; ATS &nbsp;'.rtrim(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_team_ats'], '-0').'</span>';
            }

            $dataList[$key]['logo_1'] = asset('images/logos/'.@$logos[$object->team_1_id]);
            $dataList[$key]['logo_2'] = asset('images/logos/'.@$logos[$object->team_2_id]);
            $dataList[$key]['free_game'] = $object->free_game;

            if(!empty($object->team_1_score) && !empty($object->team_2_score)){
                $dataList[$key]['winner'] = ($object->team_1_score>$object->team_2_score)?$teams[$object->team_1_id]:$teams[$object->team_2_id];
            }else{
                $dataList[$key]['winner'] = '';
            }
        }

        return view('guests.schedules', ['data_list'=>$dataList, 'market_ats'=>$market_ats, 'weeks'=>$weeks, 'seasons'=>$seasons, 'season_id'=>$seasonId, 'week_number'=>$weekNo]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLeaderboard()
    {
        $max_points = DB::table('leaderboard')->max('points');
        $stats = DB::table('leaderboard')->where('percent_win_correct', '>', 0)->OrderByDesc('points')->take(10)->get();
        $user_stats = DB::table('leaderboard')->whereNotIn('user_id', User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["admin"]);
        })->pluck('id')->toArray())->OrderByDesc('points')->latest()->paginate(10);

        return view('guests.leaderboard', ['stats'=>$stats, 'user_stats'=>$user_stats, 'max_points'=>$max_points]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function predictions(Request $request)
    {
        if ($request->isMethod('post'))
        {
            request()->validate([
                'home_team_id' => 'required',
                'away_team_id' => 'required',
                'season_id'=>'required',
                'user_id'=>'required',
                'week_no' => 'required',
            ]);

            //Restrict user to view non-purchased games predictions
            $free_combination = 0;
            $game = Game::where('team_1_id', $request->home_team_id)->where('team_2_id', $request->away_team_id)->whereSeasonId($request->season_id)->whereWeekNumber($request->week_no)->first();
            if(isset($game) && (\Carbon\Carbon::now()->lte($game->game_date) || date('Y-m-d') == $game->game_date) && $game->free_game != 'Y'){
                //return back()->withErrors(['user_id'=>['Please Purchase the Package to View Game Prediction.']]);
                $free_combination = 1;
            }

            if(!isset($game)){
                $free_combination = 1;
            }

            $paramStr = $request->user_id.",".$request->home_team_id.",".$request->away_team_id.",".$request->season_id.",".$request->week_no.",chalksportsanalytics.com";
            $url = "https://chalksportsanalytics.com/cgi-bin/beta-test4.cgi?".$paramStr;

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json"));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $resp = curl_exec($curl);
            curl_close($curl);

            $teams = Team::pluck('name','id')->toArray();
            $logos = Team::pluck('logo','id')->toArray();
            $data = json_decode($resp, true);

            return view('guests.results')
                ->with(['data'=>$data, 'teams'=>$teams, 'logos'=>$logos, 'free_combination'=>$free_combination,'week_no'=>$request->input('week_no')]);
        }
        else
        {
            $logos = Team::pluck('logo','id')->toArray();
            $teams = Team::pluck('name','id')->toArray();
            $seasons = Season::orderByDesc('id')->pluck('name', 'id')->take(1)->toArray();
            $season_id = Season::orderByDesc('id')->value('id');
            $weeks = getGameWeeks();
            $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
            $users = User::where('id', 0)->pluck('name','id')->toArray();

            return view('guests.predictions', ['teams'=>$teams, 'weeks'=>$weeks, 'week_no'=>$week_no, 'seasons'=>$seasons, 'season_id'=>$season_id, 'users'=>$users,'logos'=>$logos]);
        }
    }

    public function viewGameDetails($gameId)
    {
        $game = Game::find($gameId);
        $teams = Team::pluck('name', 'id')->toArray();
        $logos = Team::pluck('logo', 'id')->toArray();
        $records_team1 = DB::table('nfl_team_record')->where('team_id', $game->team_1_id)->first();
        $records_team2 = DB::table('nfl_team_record')->where('team_id', $game->team_2_id)->first();
        $injuries = DB::table('nfl_team_injuries')->whereIn('team_id', [$game->team_1_id, $game->team_2_id])->orderBy('team_injuries_id')->get();
        $market_values = DB::table('nfl_additional_team_odds')->where('season_id', $game->season_id)->where('week_number', $game->week_number)
            ->where('home_team_id', $game->team_1_id)->where('away_team_id', $game->team_2_id)->get();
        $matchup_predictor = DB::table('nfl_matchup_predictor')->where('week_number', $game->week_number)->where('home_team_id', $game->team_1_id)->where('away_team_id', $game->team_2_id)->first();
        $offence_team1 = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_1_id)->get();
        $defence_team1 = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_1_id)->get();
        $offence_team2 = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_2_id)->get();
        $defence_team2 = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_2_id)->get();
        $team1matches = DB::select("select IF(team_1_score > team_2_score AND team_1_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_1_score` > 0 AND `team_1_id` = ".$game->team_1_id." UNION select IF(team_2_score > team_1_score AND team_1_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_2_score` > 0 AND `team_2_id` = ".$game->team_1_id."  order by `game_date` DESC LIMIT 5");
        $team2matches = DB::select("select IF(team_2_score > team_1_score AND team_2_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_2_score` > 0 AND `team_2_id` = ".$game->team_2_id." UNION select IF(team_1_score > team_2_score AND team_1_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_1_score` > 0 AND `team_1_id` = ".$game->team_2_id."  order by `game_date` DESC LIMIT 5");
        $offense_ranks = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->orderByDesc('yards_per_game')->selectRaw("team_id, yards_per_game, ROW_NUMBER() OVER(ORDER BY yards_per_game DESC) as 'rank'")->get()->toArray();
        $defense_ranks = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->orderBy('yards_per_game')->selectRaw("team_id, yards_per_game, ROW_NUMBER() OVER(ORDER BY yards_per_game asc) as 'rank'")->get()->toArray();

        if(empty($defense_ranks)) {
            $defense_ranks = DB::table('nfl_team_defense')->where('season_id', $game->season_id)
                ->where('week_number', '=', function ($query) {
                    $query->selectRaw('max(week_number)')->from('nfl_team_defense');
                })->orderBy('yards_per_game')->selectRaw("team_id, yards_per_game, ROW_NUMBER() OVER(ORDER BY yards_per_game asc) as 'rank'")->get()->toArray();
            $defence_team1 = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', '=', function ($query) {
                    $query->selectRaw('max(week_number)')->from('nfl_team_defense');
                })->where('team_id', $game->team_1_id)->get();
            $defence_team2 = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', '=', function ($query) {
                $query->selectRaw('max(week_number)')->from('nfl_team_defense');
            })->where('team_id', $game->team_2_id)->get();
        }

        if(empty($offense_ranks)) {
            $offense_ranks = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', '=', function ($query) {
                            $query->selectRaw('max(week_number)')->from('nfl_team_offense');
                    })->orderByDesc('yards_per_game')->selectRaw("team_id, yards_per_game, ROW_NUMBER() OVER(ORDER BY yards_per_game DESC) as 'rank'")->get()->toArray();
            $offence_team1 = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', '=', function ($query) {
                        $query->selectRaw('max(week_number)')->from('nfl_team_offense');
                    })->where('team_id', $game->team_1_id)->get();
            $offence_team2 = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', '=', function ($query) {
                        $query->selectRaw('max(week_number)')->from('nfl_team_offense');
                    })->where('team_id', $game->team_2_id)->get();
        }

        $team1Ranks = [];
        $team2Ranks = [];
        foreach ($defense_ranks as $index => $defenseVal)
        {
            $offenseVal = @$offense_ranks[$index];
            if(@$offenseVal->team_id == $game->team_1_id){
                $team1Ranks['offense_rank'] = $offenseVal->rank;
                @$team1Ranks['over_all'] += $offenseVal->rank;
            }elseif(@$offenseVal->team_id == $game->team_2_id){
                $team2Ranks['offense_rank'] = $offenseVal->rank;
                @$team2Ranks['over_all'] += $offenseVal->rank;
            }

            if(@$defenseVal->team_id == $game->team_1_id){
                $team1Ranks['defense_rank'] = @$defenseVal->rank;
                @$team1Ranks['over_all'] += @$defenseVal->rank;
            }elseif(@$defenseVal->team_id == $game->team_2_id){
                $team2Ranks['defense_rank'] = @$defenseVal->rank;
                @$team2Ranks['over_all'] += @$defenseVal->rank;
            }
        }

        $show = 0;
        if(Game::whereFreeGame('Y')->whereGameId($gameId)->count() == 1){
            $show = 1;
        }

        $paramStr = "0,".$game->team_1_id.",".$game->team_2_id.",".$game->season_id.",".$game->week_number.",chalksportsanalytics.com";
        $url = "https://chalksportsanalytics.com/cgi-bin/beta-test4.cgi?".$paramStr;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $curl_data = json_decode($resp, true);

        if($game->t1_probability == '0.00' || $game->t2_probability == '0.00'){
            $game->t1_spread = number_format(@$curl_data['team_1_moneyline']['team_1_spread'], 2);
            $game->t1_money_line = number_format(abs(@$curl_data['team_1_moneyline']['team_1_moneyline']), 2, '.', '');
            $game->t1_over_under = number_format(@$curl_data['team_1_moneyline']['team_1_over_under'], 2);
            $game->t1_probability = number_format(@$curl_data['team_1_moneyline']['team_1_probability'], 2);
            $game->t2_spread = number_format(@$curl_data['team_2_moneyline']['team_2_spread'], 2);
            $game->t2_money_line = number_format(abs(@$curl_data['team_2_moneyline']['team_2_moneyline']), 2, '.', '');
            $game->t2_over_under = number_format(@$curl_data['team_2_moneyline']['team_2_over_under'], 2);
            $game->t2_probability = number_format(@$curl_data['team_2_moneyline']['team_2_probability'], 2);
            $game->save();
        }

        return view('guests.view_game')->with(['teams'=>$teams, 'logos'=>$logos, 'team1_ranks'=>$team1Ranks,'team2_ranks'=>$team2Ranks, 'data'=>$game, 'offence_team1'=>$offence_team1, 'offence_team2'=>$offence_team2, 'defence_team1'=>$defence_team1,'defence_team2'=>$defence_team2, 'records_t1'=>$records_team1,'records_t2'=>$records_team2, 'curl_data'=>$curl_data,'injuries'=>$injuries,'show'=>$show, 'matchup_predictor'=>$matchup_predictor, 'market_values'=>$market_values, 'team_1_result'=>$team1matches,'team_2_result'=>$team2matches]);

    }

    public function adjustGameRatings()
    {
        $teams = Team::pluck('name','id')->toArray();
        $logos = Team::pluck('logo','id')->toArray();
        $dataList = DB::select("SELECT
                        influence_factor_name,
                        influence_factor_weight as influence_factor_value,
                         influence_factor_id
                    FROM
                        influence_factor ");

        $data = [];
        foreach($dataList as $key => $item){
            $data[$key]['influence_factor_id'] = $item->influence_factor_id;
            $data[$key]['influence_factor_name'] = ucwords(str_replace('_',' ',$item->influence_factor_name));
            $data[$key]['influence_factor_value'] = $item->influence_factor_value;
        }

        return view('guests.adjust_rating', ['teams'=>$teams, 'logos'=>$logos, 'data'=>$data]);
    }

    public function getNFLPicks(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $weekNo = $request->week_number;
            $seasonId = $request->season_id;
            $logos = Team::pluck('logo','id')->toArray();
            $teams = Team::pluck('name','id')->toArray();

            $preData = DB::table('games as g')
                ->Distinct()
                ->leftJoin('nfl_additional_team_odds as ao', function($join) use ($seasonId, $weekNo)
                {
                    $join->on('ao.home_team_id', '=', 'g.team_1_id');
                    $join->on('ao.away_team_id', '=', 'g.team_2_id');
                    $join->where('ao.season_id', '=', $seasonId);
                    $join->where('ao.week_number', '=', $weekNo);
                })
                ->leftJoin('user_game_prediction as gp', function($join) use ($seasonId, $weekNo)
                {
                    $join->on('gp.game_id', '=', 'g.game_id');
                    $join->where('gp.season_id', '=', $seasonId);
                    $join->where('gp.week_number', '=', $weekNo);
                    $join->where('gp.user_id', '=', 0);
                })
                ->where('g.season_id', '=', $seasonId)
                ->where('g.week_number', '=', $weekNo)
                ->select('g.game_id','g.game_date','g.team_1_id','g.team_2_id','g.week_number','g.season_id','ao.spread','ao.overunder','ao.home_team_money_line','ao.away_team_money_line','gp.winner as user_winner','gp.spread as user_spread','gp.over_under as user_overunder')
                ->get();

            $dataList = [];
            $duplicateData = [];
            foreach($preData as $index => $obj){
                if(!isset($duplicateData[$obj->team_1_id][$obj->team_2_id])) {
                    $dataList[$index]['game_id'] = $obj->game_id;
                    $dataList[$index]['game_date'] = date("l, F d", strtotime($obj->game_date));
                    $dataList[$index]['game_date_short'] = date("D, M d", strtotime($obj->game_date));
                    $dataList[$index]['disable'] = (date('Y-m-d') > $obj->game_date)?'disabled="true"':'';
                    $dataList[$index]['team_1_id'] = $obj->team_1_id;
                    $dataList[$index]['team_2_id'] = $obj->team_2_id;
                    $dataList[$index]['season_id'] = $obj->season_id;
                    $dataList[$index]['week_number'] = $obj->week_number;
                    $dataList[$index]['team_1_name'] = @$teams[$obj->team_1_id];
                    $dataList[$index]['team_2_name'] = @$teams[$obj->team_2_id];
                    $dataList[$index]['team_1_name_short'] = getLastWord(@$teams[$obj->team_1_id]);
                    $dataList[$index]['team_2_name_short'] = getLastWord(@$teams[$obj->team_2_id]);
                    $dataList[$index]['team_1_logo'] = @$logos[$obj->team_1_id];
                    $dataList[$index]['team_2_logo'] = @$logos[$obj->team_2_id];
                    $dataList[$index]['team_1_money_line'] = @$obj->home_team_money_line;
                    $dataList[$index]['team_2_money_line'] = @$obj->away_team_money_line;
                    $dataList[$index]['spread'] = is_null(@$obj->spread) ? 0 : abs(@$obj->spread);
                    $dataList[$index]['over_under'] = is_null(@$obj->overunder) ? 0 : abs(@$obj->overunder);
                    $dataList[$index]['winner_name'] = is_null(@$obj->user_winner) ? '-' : @$obj->user_winner;
                    $dataList[$index]['user_spread'] = is_null(@$obj->user_spread) ? '-' : @$obj->user_spread;
                    $dataList[$index]['user_overunder'] = is_null(@$obj->user_overunder) ? '-' : @$obj->user_overunder;
                    $duplicateData[$obj->team_1_id][$obj->team_2_id] = 1;
                }
            }

            return response()->json($dataList);

        }else {
            $teams = Team::pluck('name', 'id')->toArray();
            $season_id = Season::orderByDesc('id')->value('id');
            $seasons = Season::pluck('name', 'id')->toArray();
            $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
            $weeks = getGameWeeks();

            return view('guests.nfl_picks')->with(['teams' => $teams, 'weeks' => $weeks, 'seasons' => $seasons, 'season_id' => $season_id, 'week_number' => $week_no]);
        }
    }

}
