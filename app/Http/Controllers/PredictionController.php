<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Payment;
use App\Models\Season;
use App\Models\Team;
use App\Models\User;
use App\Models\UserGame;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DB;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:prediction-list|prediction-create|prediction-edit|prediction-delete', ['only' => ['index','show']]);
         $this->middleware('permission:prediction-create', ['only' => ['create','store']]);
         $this->middleware('permission:prediction-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:prediction-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $max_points = DB::table('leaderboard')->max('points');
        $stats = DB::table('leaderboard')->where('percent_win_correct', '>', 0)->OrderByDesc('points')->take(10)->get();
        $user_stats = DB::table('leaderboard')->whereNotIn('user_id', User::with("roles")->whereHas("roles", function($q) {
                                $q->whereIn("name", ["admin"]);
                      })->pluck('id')->toArray())->OrderByDesc('points')->latest()->paginate(10);
        return view('prediction.show',['stats'=>$stats, 'user_stats'=>$user_stats, 'max_points'=>$max_points]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $logos = Team::pluck('logo','id')->toArray();
        $teams = Team::pluck('name','id')->toArray();
        $seasons = Season::orderByDesc('id')->pluck('name', 'id')->take(1)->toArray();
        $season_id = Season::orderByDesc('id')->value('id');
        $weeks = getGameWeeks();
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
        $users = User::when(!auth()->user()->hasRole('admin'), function ($q) {
            return $q->whereIn('id', [0, auth()->user()->id]);
        })->pluck('name','id')->toArray();

        return view('prediction.create', ['teams'=>$teams, 'weeks'=>$weeks, 'week_no'=>$week_no, 'seasons'=>$seasons, 'season_id'=>$season_id, 'users'=>$users,'logos'=>$logos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        if(!auth()->user()->hasRole('admin')){
            $game = Game::where('team_1_id', $request->home_team_id)->where('team_2_id', $request->away_team_id)->whereSeasonId($request->season_id)->whereWeekNumber($request->week_no)->first();

            if(isset($game) && (\Carbon\Carbon::now()->lte($game->game_date) || date('Y-m-d') == $game->game_date) && $game->free_game != 'Y'){
                $userGames = Game::whereRaw('FIND_IN_SET(game_id, (Select GROUP_CONCAT(DISTINCT games) from user_games where user_id = '.auth()->user()->id.' AND created_at >= '.Carbon::now()->startOfWeek(Carbon::TUESDAY)->format('Y-m-d').'))')
                    ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->orWhere('free_game','Y')->pluck('game_id', 'game_id')->toArray();

                if(!in_array($game->game_id, $userGames))
                    $free_combination = 1;
                    //return back()->withErrors(['user_id' => ['Please Purchase the Package to View Game Prediction.']]);
            }else
                $free_combination = 1;
        }

        $resp = $this->getPredictionCurlResponse($request->user_id,$request->home_team_id,$request->away_team_id,$request->season_id,$request->week_no);
        if(is_null($resp) || empty($resp))
            $resp = $this->getPredictionCurlResponse(0,$request->home_team_id,$request->away_team_id,$request->season_id,$request->week_no);

        $teams = Team::pluck('name','id')->toArray();
        $logos = Team::pluck('logo','id')->toArray();
        $data = json_decode($resp, true);

        return view('prediction.index')
            ->with(['data'=>$data, 'teams'=>$teams, 'logos'=>$logos, 'free_combination'=>$free_combination, 'week_no'=>$request->input('week_no')]);
    }

    public function getPredictionCurlResponse($userId, $homeTeamId, $awayTeamId, $seasonId, $weekNo)
    {
        $paramStr = $userId.",".$homeTeamId.",".$awayTeamId.",".$seasonId.",".$weekNo.",chalksportsanalytics.com";
        $url = "https://chalksportsanalytics.com/cgi-bin/beta-test4.cgi?".$paramStr;

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;
    }

    /**
     * Display the specified resource.
     * @param  $weekNo
     */
    public function show($weekNseasons)
    {
        $weekNseason = explode('_', $weekNseasons);
        $weekNo = @$weekNseason[0];
        $seasonId = @$weekNseason[1];

        $logos = Team::pluck('logo','id')->toArray();
        $teams = Team::pluck('name','id')->toArray();
        //$recExist = DB::table('user_game_prediction')->where('user_id','=',auth()->user()->id)->where('season_id', '=', $seasonId)->where('week_number','=',$weekNo)->first();

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
                            $join->where('gp.user_id', '=', auth()->user()->id);
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
                $dataList[$index]['disable'] = (date('Y-m-d') > $obj->game_date || auth()->user()->hasrole('admin'))?'disabled="true"':'';
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        $season_id = Season::orderByDesc('id')->value('id');
        $seasons = Season::pluck('name', 'id')->toArray();
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
        $weeks = getGameWeeks();

        return view('prediction.edit')->with(['teams'=>$team, 'weeks'=>$weeks, 'seasons'=>$seasons, 'season_id'=>$season_id, 'week_no'=>$week_no]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        request()->validate([
            'season_id'=>'required',
            'week_number' => 'required',
            'game_id' => 'required',
            'field_name' => 'required',
            'field_value' => 'required',
        ]);

        DB::table('user_game_prediction')
            ->updateOrInsert(
                ['user_id' => auth()->user()->id, 'username'=>auth()->user()->name, 'week_number' => $request->week_number, 'season_id' => $request->season_id, 'game_id' => $request->game_id],
                [$request->field_name => $request->field_value]
            );

        return "success";//redirect()->back()->with('success','Predictions updated successfully.');
    }
}
