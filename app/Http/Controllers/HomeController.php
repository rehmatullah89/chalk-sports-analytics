<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Payment;
use App\Models\Season;
use Carbon\Carbon;
use App\Models\Team;
use App\Models\Game;
use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $market_ats = [];
        $season_id = Season::orderByDesc('id')->value('id');
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
        $teams = Team::pluck('name', 'id')->toArray();
        $logos = Team::pluck('logo', 'id')->toArray();
        $team_records = DB::table('nfl_team_record')->get();
        $packages = Package::where('status','Active')->orderBy('order')->orderBy('subscription_price')->get();
        //->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->whereBetween('game_date', [Carbon::now()->startOfWeek(Carbon::TUESDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)])
        $games = Game::where('week_number', $week_no)->where('season_id',$season_id)->where('free_game', 'N')->OrderBy('game_date')->get();
        $market_values = DB::table('nfl_additional_team_odds')->whereSeasonId($season_id)->get();
        foreach ($market_values as $values){
            $market_ats[$values->week_number][$values->away_team_id][$values->home_team_id] = ['over_under'=>$values->overunder,'spread'=>$values->spread,'home_money_line'=>$values->home_team_money_line,'away_money_line'=>$values->away_team_money_line,'home_team_ats'=>$values->home_team_ats,'away_team_ats'=>$values->away_team_ats];
        }

        $freeGames = Game::whereBetween('game_date', [Carbon::now()->startOfWeek(Carbon::TUESDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)])
            ->where('free_game', 'Y')->OrderBy('game_date', 'desc')->get();

        //->where('game_date', ">=", Carbon::now()->format('Y-m-d'))
        $userGames = Game::whereRaw('FIND_IN_SET(game_id, (Select GROUP_CONCAT(DISTINCT games) from user_games where user_id = '.auth()->user()->id.' AND created_at >= '.Carbon::now()->startOfWeek(Carbon::TUESDAY)->format('Y-m-d').'))')
            ->whereBetween('game_date', [Carbon::now()->startOfWeek(Carbon::TUESDAY)->format('Y-m-d'), Carbon::now()->endOfWeek(Carbon::MONDAY)->format('Y-m-d')])->OrderBy('game_date')->get();

        $pastGames = Game::whereRaw('FIND_IN_SET(game_id, (Select GROUP_CONCAT(DISTINCT games) from user_games where user_id = '.auth()->user()->id.'))')
            ->where('game_date', "<", Carbon::now()->format('Y-m-d'))->where('free_game', 'N')
            ->orWhereIn('game_id', function($query){
                $query->select('game_id')->from('games')->where('free_game', 'Y')->where('game_date', "<", Carbon::now()->format('Y-m-d'));
            })->OrderBy('game_date', 'desc')->get();

        $subscriptions = Payment::where("user_id", auth()->user()->id)
            ->where('active', 'Y')->where('payment_type', 'subscription')->orderBy('id')->pluck("package_id", "package_id")->toArray();

        $singlePayments = Payment::where("user_id", auth()->user()->id)
            ->where('active', 'Y')->where('payment_type', 'single_payment')->orderBy('id')->pluck("package_id", "package_id")->toArray();

        return view('home', ['games' => $games, 'teams' => $teams, 'logos' => $logos, 'team_records'=>$team_records, 'packages' => $packages,'market_ats'=>$market_ats, 'free_games'=>$freeGames, 'user_games'=>$userGames, 'subscriptions'=>$subscriptions, 'single_payments'=>$singlePayments, 'past_games'=>$pastGames, 'season_id'=>$season_id, 'week_number'=>$week_no]);
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}
