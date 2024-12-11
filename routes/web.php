<?php

use App\Models\Season;
use Carbon\Carbon;
use App\Models\Game;
use App\Models\Team;
use App\Models\Package;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SubscriptionController;

Route::get('', function () {
    if(\Auth::check()) {
        return redirect('home');
    }else{
        $market_ats=[];
        $teams = Team::pluck('name', 'id')->toArray();
        $logos = Team::pluck('logo', 'id')->toArray();
        $seasonId = Season::orderByDesc('id')->value('id');
        $weekNo = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');
        $packages = Package::where('status','Active')->orderBy('order')->orderBy('subscription_price')->get();
        $games = Game::whereBetween('game_date', [Carbon::now()->startOfWeek(Carbon::TUESDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)])->OrderBy('game_date')->get();
        $freeGames = Game::whereBetween('game_date', [Carbon::now()->startOfWeek(Carbon::TUESDAY), Carbon::now()->endOfWeek(Carbon::MONDAY)])
            ->where('free_game', 'Y')->OrderBy('game_date', 'desc')->get();
        $market_values = DB::table('nfl_additional_team_odds')->whereSeasonId(3)->get();
        $team_records = DB::table('nfl_team_record')->get();
        foreach ($market_values as $values){
            $market_ats[$values->week_number][$values->away_team_id][$values->home_team_id] = ['over_under'=>$values->overunder,'spread'=>$values->spread,'home_money_line'=>$values->home_team_money_line,'away_money_line'=>$values->away_team_money_line,'home_team_ats'=>$values->home_team_ats,'away_team_ats'=>$values->away_team_ats];
        }

        return view('index', ['games' => $games, 'market_ats'=>$market_ats, 'team_records'=>$team_records, 'season_id'=>$seasonId, 'week_number'=>$weekNo,'teams' => $teams, 'logos' => $logos, 'packages' => $packages, 'free_games'=>$freeGames]);
    }
});

Auth::routes();

Route::stripeWebhooks('stripe-payment-success');
Route::stripeWebhooks('stripe-payment-declined');

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/payment/{package}/{type}', 'App\Http\Controllers\PackageController@payment')->name('payment');
    Route::post('/save-payment','App\Http\Controllers\PackageController@savePayment')->name('save-payment');
    Route::post('/free-game','App\Http\Controllers\GameController@setFreeGame')->name('free-game');
    Route::any('/run-test','App\Http\Controllers\GameController@getTestReport')->name('run-test');
    Route::any('/run-test-report','App\Http\Controllers\GameController@saveTestReport')->name('run-test-report');
    Route::any('/game-report/{gameId}','App\Http\Controllers\GameController@getGameReport')->name('game-report');
    Route::any('/nfl3-games','App\Http\Controllers\GameController@getNfl3Games')->name('nfl3-games');
    Route::post('/save-nfl3-games','App\Http\Controllers\GameController@saveNfl3Games')->name('save-nfl3-games');
    Route::get('/influence-factors/{team}/{week}/{season}/{user?}','App\Http\Controllers\GameController@getInfluenceFactors')->name('influence-factors');
    Route::post('import-prices', [PackageController::class, 'importPrices']);
    Route::any('sort-datatable', [PackageController::class, 'sortDataTable']);


    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('prediction', PredictionController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('games', GameController::class);
    Route::resource('subscriptions', SubscriptionController::class);

});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/schedules/{season_id}/{week_number}','App\Http\Controllers\GuestController@getSchedules')->name('schedules');
    Route::get('/leaderboard','App\Http\Controllers\GuestController@getLeaderboard')->name('leaderboard');
    Route::any('/predictions','App\Http\Controllers\GuestController@predictions')->name('predictions');
    Route::any('/view-game/{gameId}','App\Http\Controllers\GuestController@viewGameDetails')->name('view-game');
    Route::any('/adjust-rating','App\Http\Controllers\GuestController@adjustGameRatings')->name('adjust-rating');
    Route::any('/nfl-picks','App\Http\Controllers\GuestController@getNFLPicks')->name('nfl-picks');
});
