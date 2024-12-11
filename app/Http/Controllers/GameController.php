<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Season;
use App\Models\User;
use App\Models\UserGame;
use DB;
use Carbon\Carbon;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:game-list|influence-factor-update|weight-factor-create|game-delete', ['only' => ['index','show']]);
        $this->middleware('permission:weight-factor-create', ['only' => ['create','store']]);
        $this->middleware('permission:influence-factor-update', ['only' => ['edit','update']]);
        $this->middleware('permission:game-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $season_id = Season::orderByDesc('id')->value('id');
        $seasons = Season::pluck('name', 'id')->toArray();
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
        $weeks = getGameWeeks();

        return view('games.index', ['weeks'=>$weeks, 'seasons'=>$seasons, 'season_id'=>$season_id, 'week_number'=>$week_no]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = DB::table('influence_factor')->orderBy('sort_order')->get();
        return view('games.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->has('list')){
            foreach($request->input('list') as $key => $value){
                DB::table('influence_factor')
                    ->updateOrInsert(
                        ['influence_factor_id' => $value['influence_factor_id']],
                        $value
                    );
            }
        }
        $data = DB::table('influence_factor')->orderBy('sort_order')->get();

        return redirect()->route('games.create')
            ->with(['success'=>'Weight Factors updated successfully.','data'=>$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($weekNseasons)
    {
        $market_ats = [];
        $weekNseason = explode('_', $weekNseasons);
        $weekNo = @$weekNseason[0];
        $seasonId = @$weekNseason[1];

        $teams = Team::pluck('name','id')->toArray();
        $logos = Team::pluck('logo','id')->toArray();
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
            $dataList[$key]['team_1_score'] = ($object->team_1_score>0 && $object->team_2_score>0)?$object->team_1_score:'';
            $dataList[$key]['team_2_score'] = ($object->team_1_score>0 && $object->team_2_score>0)?$object->team_2_score:'';
            $dataList[$key]['team_1'] = $teams[$object->team_1_id];
            $dataList[$key]['team_2'] = $teams[$object->team_2_id];
            $dataList[$key]['team_1_short'] = getLastWord($teams[$object->team_1_id]);
            $dataList[$key]['team_2_short'] = getLastWord($teams[$object->team_2_id]);


            $dataList[$key]['away_ats'] = '';
            $dataList[$key]['home_ats'] = '';
            if(isset($market_ats[$object->week_number][$object->team_2_id][$object->team_1_id])){
                $dataList[$key]['away_ats'] = '<span  class="badge bg-secondary">'.((@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_money_line'] < @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread']):addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread'])).'</span><span  class="badge bg-secondary">'.(addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_money_line'])).'</span><span  class="badge bg-secondary">'.(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['over_under']).'</span><span class="badge bg-secondary"> ATS &nbsp;'.rtrim(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_team_ats'], '-0').'</span>';
                $dataList[$key]['home_ats'] = '<span  class="badge bg-secondary">'.((@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_money_line'] > @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['away_money_line'])?addPlusSymbol(-1* @$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread']):addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['spread'])).'</span><span  class="badge bg-secondary">'.(addPlusSymbol(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_money_line'])).'</span><span  class="badge bg-secondary">'.(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['over_under']).'</span><span class="badge bg-secondary"> ATS &nbsp;'.rtrim(@$market_ats[$object->week_number][$object->team_2_id][$object->team_1_id]['home_team_ats'], '-0').'</span>';
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

        return response()->json($dataList);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teams = Team::pluck('name','id')->toArray();
        $logos = Team::pluck('logo','id')->toArray();
        $seasons = Season::pluck('name', 'id')->toArray();
        $season_id = Season::orderByDesc('id')->value('id');
        $users = User::pluck('name', 'id')->toArray();
        $weeks = getGameWeeks();
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
        $purchased = Payment::where('user_id', auth()->user()->id)->where('active', 'Y')->where('package_id', 1)->count();

        return view('games.edit', ['teams'=>$teams, 'weeks'=>$weeks, 'logos'=>$logos, 'week_no'=>$week_no, 'seasons'=>$seasons, 'season_id'=>$season_id, 'users'=>$users, 'purchased'=>$purchased]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(is_null($request->user_id))
            $request["user_id"] = [auth()->user()->id];

        if($request->has('list') && $request->has('week_no')){
            foreach ($request->input('week_no') as $weekNo) {
                foreach ($request->input('user_id') as $userId) {
                    foreach ($request->input('list') as $key => $value) {
                        DB::table('team_influence_factor')
                            ->updateOrInsert(
                                ['team_id' => $request->home_team_id, 'users_id' => $userId, 'season_id' => $request->season_id, 'week_number' => $weekNo, 'influence_factor_id' => $value['influence_factor_id']],
                                $value
                            );
                    }
                }
            }
        }

        return redirect()->back()->with('success','Team Influence factors updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get Influence factors for a team.
     *
     * @param  int  $teamId
     * @param  int  $weekNo
     * @return \Illuminate\Http\Response
     */
    public function getInfluenceFactors($teamId, $weekNo, $seasonId, $userIds)
    {
        $weekNo = explode(",", $weekNo);
        $userId = explode(",", $userIds);

        $dataList = DB::select("SELECT
                        i.influence_factor_name,
                        tif.influence_factor_value,
                        tif.influence_factor_id
                    FROM
                        influence_factor i,
                        team_influence_factor tif
                    WHERE
                        tif.influence_factor_id = i.influence_factor_id
                    AND tif.team_id = " . $teamId .
                            " AND tif.users_id = ".@$userId[0]." AND tif.week_number = " . @$weekNo[0] .
            " AND tif.season_id = ".$seasonId." ORDER BY i.sort_order;");

        if(count($dataList) == 0){
            $dataList = DB::select("SELECT
                        influence_factor_name,
                        influence_factor_weight as influence_factor_value,
                         influence_factor_id
                    FROM
                        influence_factor ");
        }

        $data = [];
        foreach($dataList as $key => $item){
            $data[$key]['influence_factor_id'] = $item->influence_factor_id;
            $data[$key]['influence_factor_name'] = ucwords(str_replace('_',' ',$item->influence_factor_name));
            $data[$key]['influence_factor_value'] = $item->influence_factor_value;
        }

        return response()->json($data);
    }

    /**
     * Get game Summary Report
     *
     * @param  int  $gameId
     * @return \Illuminate\Http\Response
     */
    public function getGameReport($gameId)
    {
        $show = 1;
        if(!auth()->user()->hasRole('admin') && Game::whereFreeGame('Y')->whereGameId($gameId)->count() == 0){
            $show = 0;

            $purchasedGames = Game::whereRaw('FIND_IN_SET(game_id, (Select GROUP_CONCAT(DISTINCT games) from user_games where user_id = '.auth()->user()->id.'))')
                ->where('free_game', 'N')->OrderBy('game_date', 'desc')->pluck('game_id')->toArray();

            if(count($purchasedGames)>0){
                if(in_array($gameId, $purchasedGames))
                    $show = 1;
            }
        }

        $game = Game::find($gameId);
        $teams = Team::pluck('name', 'id')->toArray();
        $logos = Team::pluck('logo', 'id')->toArray();
        $records_team1 = DB::table('nfl_team_record')->where('team_id', $game->team_1_id)->first();
        $records_team2 = DB::table('nfl_team_record')->where('team_id', $game->team_2_id)->first();
        $injuries = DB::table('nfl_team_injuries')->whereIn('team_id', [$game->team_1_id, $game->team_2_id])->orderBy('team_injuries_id')->get();
        $offense_ranks = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->orderByDesc('yards_per_game')->selectRaw("team_id, yards_per_game, ROW_NUMBER() OVER(ORDER BY yards_per_game DESC) as 'rank'")->get()->toArray();
        $defense_ranks = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->orderBy('yards_per_game')->selectRaw("team_id, yards_per_game, ROW_NUMBER() OVER(ORDER BY yards_per_game ASC) as 'rank'")->get()->toArray();
        $offence_team1 = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_1_id)->get();
        $defence_team1 = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_1_id)->get();
        $offence_team2 = DB::table('nfl_team_offense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_2_id)->get();
        $defence_team2 = DB::table('nfl_team_defense')->where('season_id', $game->season_id)->where('week_number', $game->week_number)->where('team_id', $game->team_2_id)->get();
        $market_values = DB::table('nfl_additional_team_odds')->where('season_id', $game->season_id)->where('week_number', $game->week_number)
            ->where('home_team_id', $game->team_1_id)->where('away_team_id', $game->team_2_id)->get();
        $matchup_predictor = DB::table('nfl_matchup_predictor')->where('week_number', $game->week_number)->where('home_team_id', $game->team_1_id)->where('away_team_id', $game->team_2_id)->first();

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
                $game->t1_money_line = number_format((@$curl_data['team_1_moneyline']['team_1_moneyline']), 2, '.', '');
                $game->t1_over_under = number_format(@$curl_data['team_1_moneyline']['team_1_over_under'], 2);
                $game->t1_probability = number_format(@$curl_data['team_1_moneyline']['team_1_probability'], 2);
                $game->t2_spread = number_format(@$curl_data['team_2_moneyline']['team_2_spread'], 2);
                $game->t2_money_line = number_format((@$curl_data['team_2_moneyline']['team_2_moneyline']), 2, '.', '');
                $game->t2_over_under = number_format(@$curl_data['team_2_moneyline']['team_2_over_under'], 2);
                $game->t2_probability = number_format(@$curl_data['team_2_moneyline']['team_2_probability'], 2);
                $game->save();
        }

        $team1matches = DB::select("select IF(team_1_score > team_2_score AND team_1_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_1_score` > 0 AND `team_1_id` = ".$game->team_1_id." UNION select IF(team_2_score > team_1_score AND team_1_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_2_score` > 0 AND `team_2_id` = ".$game->team_1_id."  order by `game_date` DESC LIMIT 5");
        $team2matches = DB::select("select IF(team_2_score > team_1_score AND team_2_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_2_score` > 0 AND `team_2_id` = ".$game->team_2_id." UNION select IF(team_1_score > team_2_score AND team_1_score > 0, 'WIN', 'LOST') as Result,game_date from `games` where `team_1_score` > 0 AND `team_1_id` = ".$game->team_2_id."  order by `game_date` DESC LIMIT 5");

        return view('games.show')->with(['teams'=>$teams, 'logos'=>$logos, 'team1_ranks'=>$team1Ranks,'team2_ranks'=>$team2Ranks, 'data'=>$game, 'market_values'=>$market_values, 'records_t1'=>$records_team1,'records_t2'=>$records_team2, 'offence_team1'=>$offence_team1, 'offence_team2'=>$offence_team2, 'defence_team1'=>$defence_team1,'defence_team2'=>$defence_team2, 'injuries'=>$injuries, 'curl_data'=>$curl_data, 'team_1_result'=>$team1matches,'team_2_result'=>$team2matches, 'show'=>$show, 'matchup_predictor'=>$matchup_predictor]);
    }

    public function getTestReport()
    {
        $users = User::pluck('name','id')->toArray();
        $summary = DB::table('test_report_summary')->first();
        $seasons = Season::pluck('name', 'id')->toArray();
        $team_list = [0=>'All Teams']+Team::pluck('name','id')->toArray();
        $season_id = Season::orderByDesc('id')->value('id');
        $weeks = getGameWeeks();
        $chart_data = DB::table('game_test')->selectRaw("SUM(IF(correct_winner=1, 1, 0)) AS wins, SUM(IF(correct_winner=0, 1, 0)) AS lost")->get();
        $teams = DB::select("SELECT team_name, SUM(count) count, SUM(wins) wins, id from ( select t.name as team_name, count(1) as count, SUM(IF(g.team_1_id=g.winner, 1, 0)) AS wins, t.id from teams t, game_test g where t.id=g.team_1_id group by t.id,t.name UNION select t.name as team_name, count(1) as count, SUM(IF(g.team_2_id=g.winner, 1, 0)) AS wins, t.id from teams t, game_test g where t.id=g.team_2_id group by t.id,t.name ) t group by id, team_name");

        $data = DB::table('game_test as g')
            ->join('teams as t', 'g.team_1_id', '=', 't.id')
            ->join('teams as t2', 'g.team_2_id', '=', 't2.id')
            ->join('teams as t3', 'g.winner', '=', 't3.id')
            ->join('teams as t4', 'g.p_winner', '=', 't4.id')
            ->where('g.correct_winner', '=', 0)
            ->select('t.name as t_name', 't2.name as t2_name', 't3.name as t3_name', 'g.team_1_score', 'g.team_2_score', 'g.team_1_p','g.team_2_p','t4.name','g.actual_mov','g.predicted_mov','g.act_pts','g.p_pts','g.correct_winner','t3.id','t2.id','g.week_number','g.team_1_id','g.team_2_id')
            ->orderByDesc('g.week_number')
            ->latest('g.created_at')->get();

        $data2 = DB::table('game_test as g')
            ->join('teams as t', 'g.team_1_id', '=', 't.id')
            ->join('teams as t2', 'g.team_2_id', '=', 't2.id')
            ->join('teams as t3', 'g.winner', '=', 't3.id')
            ->join('teams as t4', 'g.p_winner', '=', 't4.id')
            ->select('t.name as t_name', 't2.name as t2_name', 't3.name as t3_name', 'g.team_1_score', 'g.team_2_score', 'g.team_1_p','g.team_2_p','t4.name','g.actual_mov','g.predicted_mov','g.act_pts','g.p_pts','g.correct_winner','t3.id','t2.id','g.week_number','g.team_1_id','g.team_2_id')
            ->orderByDesc('g.week_number')
            ->latest('g.created_at')->get();

        return view('games.test-report')->with(['users'=>$users, 'teams'=>$teams, 'summary'=>$summary, 'data'=>$data, 'data2'=>$data2, 'seasons'=>$seasons, 'season_id'=>$season_id, 'team_list'=>$team_list, 'weeks'=>$weeks, 'chart_data'=>$chart_data]);
    }

    public function saveTestReport(Request $request)
    {
        request()->validate([
            'season_id'=>'required',
            'week_number'=>'required',
            'team_1_id'=>'required',
            'team_2_id'=>'required',
            'user_id'=>'required',
        ]);

        DB::table('test_report_summary')->delete();
        DB::table('test_report_summary')->insert(['updated_at'=>date('Y-m-d H:i:s')]+$request->except(['_token']));

        $paramStr = $request->user_id.",".$request->team_1_id.",".$request->team_2_id.",".$request->season_id.",".$request->week_number.",chalksportsanalytics.com";
        $url = "https://chalksportsanalytics.com/cgi-bin/testAlgorithm.cgi?".$paramStr;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);

        return response()->json($resp);
    }

    function setFreeGame(Request $request)
    {
        request()->validate([
            'game_id'=>'required',
            'free_game'=>'required',
        ]);

        Game::whereGameId($request->game_id)
            ->update(['free_game' => $request->input('free_game')]);

        return redirect()->route('games.index')
            ->with(['success'=>'Game status updated successfully.']);
    }

    public function getNfl3Games(Request $request)
    {
        $seasons = Season::pluck('name', 'id')->toArray();
        $season_id = Season::orderByDesc('id')->value('id');
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
        $weeks = Game::groupBy('week_number')->where('week_number','>=',$week_no)->where('season_id', $season_id)->pluck('week_number','week_number')->toArray();
        $games = Game::whereSeasonId($season_id)->whereWeekNumber($week_no)->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->where('game_time','<>','8:20')->where("free_game", "N")->get();
        $weekGames = explode(',', DB::table('3nfl_user_games')->whereSeasonId($season_id)->whereWeekNumber($week_no)->whereUserId(auth()->user()->id)->value('games'));
        $teams = Team::pluck('name','id')->toArray();

        if($request->isMethod('post')){
            $weekGames = explode(',', DB::table('3nfl_user_games')->whereSeasonId($request->season_id)->whereWeekNumber($request->week_number)->whereUserId(auth()->user()->id)->value('games'));
            $games = Game::whereSeasonId($request->season_id)->whereWeekNumber($request->week_number)->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->where('game_time','<>','8:20')->where("free_game", "N")->get();

            $time = strtotime(@$games[0]->game_date);
            $saturday = date('Y-m-d', strtotime('saturday', $time));
            $sunday = date('Y-m-d', strtotime('sunday', $time));

            $data = "";
            $indexCounter = 0;
            foreach($games as $game){
                if($game->game_date == $sunday || $game->game_date == $saturday){
                        if($indexCounter%4 == 0) {
                            $data .= '<tr>';
                        }

                        $data .= '<td style="background: none;"><div class="form-check form-check-inline">';
                        $data .= '<input class="form-check-input" name="games[]" type="checkbox" '.(in_array($game->game_id,$weekGames)?"checked":"").' '.((@$weekGames[0] != "")?"disabled":"").'  id="game'.$game->game_id.'" value="'.$game->game_id.'" onchange="selectGames(8);">';
                        $data .= '<label class="form-check-label" for="inlineCheckbox1">'.getLastWord(@$teams[$game->team_2_id]).'&nbsp;@&nbsp;'.getLastWord(@$teams[$game->team_1_id]).'</label>';
                        $data .= '</div></td>';

                       $indexCounter++;
                       if($indexCounter%4 == 4){
                            $data .= '</tr>';
                       }
                }
            }
            return response()->json($data);
        }

        return view('games.3nfl_games')->with(['week_no'=>$week_no, 'seasons'=>$seasons, 'season_id'=>$season_id,'weeks'=>$weeks, 'games'=>$games, 'teams'=>$teams, 'user_games'=>$weekGames]);
    }

    public function saveNfl3Games(Request $request)
    {
        if(!empty($request->games)) {
            DB::table('3nfl_user_games')
                ->updateOrInsert(
                    ['user_id' => auth()->user()->id, 'season_id' => $request->season_id, 'week_number' => $request->week_no],
                    ['games' => implode(',', $request->games)]
                );

            return redirect()->route('home')
                ->with('success', 'You have successfully selected games for week: ' . $request->week_no . '.');
        }else
            return redirect()->back()->with('success','You have already selected games for week: '.$request->week_no);
    }

}
