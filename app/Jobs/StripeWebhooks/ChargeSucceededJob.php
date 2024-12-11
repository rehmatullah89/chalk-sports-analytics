<?php

namespace App\Jobs\StripeWebhooks;

use Carbon\CarbonImmutable;
use DB;
use Stripe;
use Carbon\Carbon;
use App\Models\Game;
use App\Models\User;
use App\Models\UserGame;
use App\Models\Season;
use App\Models\Package;
use App\Models\Payment;
//use http\Client\Curl\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class ChargeSucceededJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
        // you can access the payload of the webhook call with `$this->webhookCall->payload`
        $charge = $this->webhookCall->payload['data']['object'];
        $user = User::where('stripe_id','=',$charge['customer'])->first();

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $charge = $stripe->invoices->retrieve(
            $charge['invoice'],
            []
        );

        $package = Package::where('subscription_id','=',@$charge['lines']['data'][0]['plan']['id'])->first();

        if($user) {
            Payment::create([
                's_payment_id' => $charge['charge'],
                'invoice_id' => $charge['id'],
                'amount' => $charge['amount_paid'],
                'user_id' => $user->id,
                'package_id' => $package->id,
                'payment_type' => 'subscription',
                'status' => 'success',
            ]);

            if($package->id != 1){
                $games = $this->selectPackageGames($package->id);
                $games = is_array($games)?implode(",", $games):'';
                UserGame::create(['user_id'=>$user->id,'package_id'=>$package->id,'games'=>$games,'status'=>'active']);
            }
        }
    }

    public function selectPackageGames($packageId)
    {
        $weekStartDate = CarbonImmutable::now()->startOfWeek(Carbon::TUESDAY);
        $weekEndDate = CarbonImmutable::now()->endOfWeek(Carbon::MONDAY);

        $season = Season::orderByDesc('id')->first();
        $games = Game::where('game_date', ">=", $weekStartDate->format('Y-m-d'))->where("free_game", "N");

        //#These are full week games pack#
        if($packageId == 3){
            $games->whereBetween('game_date', [$weekStartDate->format('Y-m-d'), $weekEndDate->format('Y-m-d')]);
        }
        //#It is full season package#
        elseif($packageId == 2){
            $games->whereBetween('game_date', [$season->start_date, $season->end_date]);
        }

        //#These are sunday,monday,sundayNight,thursdayNight games respectively#
        if(in_array($packageId,[4,5,6,7,8])){
            $thursDayDate = $weekStartDate->addDays(2)->format('Y-m-d');
            $sunDayDate = $weekStartDate->addDays(5)->format('Y-m-d');
            $monDayDate = $weekStartDate->addDays(6)->format('Y-m-d');

            if($packageId == 5)
                $games->where('game_date', $monDayDate);
            elseif ($packageId == 7){
                //if thursday passed get games of next thursday
                if(\Carbon\Carbon::now()->gt($thursDayDate) && $thursDayDate != date("Y-m-d")){
                    $thursDayDate = \Carbon\Carbon::now()->next('Thursday')->format('Y-m-d');
                }
                $games->where('game_date', $thursDayDate);
            }
            elseif($packageId != 8){
                //if sunday passed get games for next sundayS
                if(\Carbon\Carbon::now()->gt($sunDayDate)){
                    $sunDayDate = \Carbon\Carbon::now()->next('Sunday')->format('Y-m-d');
                }
                if($packageId == 6)
                    return $games->where('game_date', $sunDayDate)->OrderByDesc('game_id')->take(1)->pluck('game_id')->toArray();
                else
                    $games->where('game_date', $sunDayDate);
            }

            if($packageId == 8){

                $sundayNightGame = 0;
                //#These are 3 games selection#
                if(\Carbon\Carbon::now()->gt($sunDayDate) && $sunDayDate != date("Y-m-d")){
                    $weekStartDate = CarbonImmutable::now()->next('Tuesday')->startOfWeek(Carbon::TUESDAY);
                    $weekEndDate = CarbonImmutable::now()->next('Sunday')->endOfWeek(Carbon::MONDAY);

                    $thursDayDate = $weekStartDate->addDays(2)->format('Y-m-d');
                    $sunDayDate = $weekStartDate->addDays(5)->format('Y-m-d');
                    $monDayDate = $weekStartDate->addDays(6)->format('Y-m-d');

                    $sundayNightGame = Game::where('game_date', ">=", $weekStartDate->format('Y-m-d'))->where("free_game", "N")
                        ->where('game_date', $sunDayDate)->OrderByDesc('game_id')->take(1)->get()->toArray();
                }
                $games->whereBetween('game_date', [$weekStartDate->format('Y-m-d'), $weekEndDate->format('Y-m-d')])
                    ->where('game_id', '!=',$sundayNightGame)->whereNotIn('game_date', [$thursDayDate, $monDayDate]);
            }
        }

        if($packageId == 8) {
            return $games->OrderBy('game_date')->take(3)->pluck('game_id')->toArray();
        }

        return $games->OrderBy('game_date')->pluck('game_id')->toArray();
    }
}
