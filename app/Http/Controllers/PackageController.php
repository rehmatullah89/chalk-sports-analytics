<?php

namespace App\Http\Controllers;

use DB;
use Stripe;
use Session;
use Carbon\Carbon;
use App\Models\Game;
use App\Models\Team;
use App\Models\Season;
use App\Models\Package;
use App\Models\Payment;
use App\Models\UserGame;
use Carbon\CarbonImmutable;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Imports\PricesImport;
use Maatwebsite\Excel\Facades\Excel;

class PackageController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
         $this->middleware('permission:package-list|package-create|package-edit|package-delete', ['only' => ['index','store']]);
         $this->middleware('permission:package-create', ['only' => ['create','store']]);
         $this->middleware('permission:package-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:package-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Package::orderBy('order')->get();
        $subscriptions = Payment::where("user_id", auth()->user()->id)
            ->where('active', 'Y')->where('payment_type', 'subscription')->orderBy('id')->pluck("package_id", "package_id")->toArray();

        $singlePayments = Payment::where("user_id", auth()->user()->id)
            ->where('active', 'Y')->where('payment_type', 'single_payment')->orderBy('id')->pluck("package_id", "package_id")->toArray();

        return view('packages.index')->with(['data'=>$data, 'subscriptions'=>$subscriptions, 'single_payments'=>$singlePayments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:packages,name',
            'price' => 'required',
            'subscription_price' => 'required',
            'status' => 'required',
            'detail' => 'required',
        ]);
        $request->request->add(['identifier' => str_replace(' ', '', $request->name)]);

        Package::create($request->all());

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Package::find($id);

        return view('packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package = Package::find($id);

        return view('packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'subscription_price' => 'required',
            'status' => 'required',
            'detail' => 'required'
        ]);

        $package->update($request->all());

        return redirect()->route('packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Package::find($id)->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Package deleted successfully');
    }

    public function payment($packageId)
    {
        $season_id = Season::orderByDesc('id')->value('id');
        $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');

        $data = [
            'week_no' => $week_no,
            'season_id' => $season_id,
            'weeks'=> Game::groupBy('week_number')->where('week_number','>=',$week_no)->where('season_id', $season_id)->pluck('week_number','week_number')->toArray(),
            'teams'=> Team::pluck('name', 'id')->toArray(),
            'games' => $this->selectPackageGames($packageId),
            'intent' => auth()->user()->createSetupIntent(),
            'package' => Package::find($packageId),
            'free_games'=>$this->getThisWeekFreeGames()
        ];

        return view('packages.payment')->with($data);
    }

    public function getThisWeekFreeGames()
    {
        $weekStartDate = CarbonImmutable::now()->startOfWeek(Carbon::TUESDAY);
        $weekEndDate = CarbonImmutable::now()->endOfWeek(Carbon::MONDAY);

        $season = Season::orderByDesc('id')->first();
        $games = Game::whereBetween('game_date', [$weekStartDate->format('Y-m-d'), $weekEndDate->format('Y-m-d')])->where("free_game", "Y")->get();

        return $games;
    }

    public function selectPackageGames($packageId)
    {
        $weekStartDate = CarbonImmutable::now()->startOfWeek(Carbon::TUESDAY);
        $weekEndDate = CarbonImmutable::now()->endOfWeek(Carbon::MONDAY);

        $season = Season::orderByDesc('id')->first();
        $games = Game::where('game_date', ">=", $weekStartDate->format('Y-m-d'))->where("free_game", "N");

        //#These are full week games#
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
                    return $games->where('game_date', $sunDayDate)->OrderByDesc('game_id')->take(1)->get();
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

        return $games->OrderBy('game_date')->get();
    }

    public function savePayment(Request $request)
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $this->validate($request, [
            'token' => 'required'
        ]);

        $package = Package::where('identifier', $request->identifier)->first();
        if ($request->pkg_type == 'purchase') {
            $user = $request->user();
            $paymentMethod = $request->input('token');

            try {
                $user->createOrGetStripeCustomer();
                $user->updateDefaultPaymentMethod($paymentMethod);
                $user->charge($package->price, $paymentMethod);

                $games = is_array($request->games) ? implode(",", $request->games) : '';

                Payment::create([
                    's_payment_id' => $paymentMethod,
                    'invoice_id' => $package->stripe_id,
                    'amount' => $package->price,
                    'user_id' => auth()->user()->id,
                    'package_id' => $package->id,
                    'payment_type' => 'single_payment',
                    'status' => 'success',
                ]);

                if ($package->id == 2) {
                    Payment::create([
                        's_payment_id' => $paymentMethod,
                        'invoice_id' => $package->stripe_id,
                        'amount' => $package->price,
                        'user_id' => auth()->user()->id,
                        'package_id' => 1,
                        'payment_type' => 'single_payment',
                        'status' => 'success',
                    ]);
                }

                UserGame::create(['user_id' => auth()->user()->id, 'package_id' => $package->id, 'games' => $games, 'status' => 'active']);

            } catch (\Exception $exception) {
                return back()->with('error', $exception->getMessage());
            }
        } else {

            $season_id = Season::orderByDesc('id')->value('id');
            $games = is_array($request->games) ? implode(",", $request->games) : '';
            $week_no = (int)Game::whereBetween('game_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('game_date', ">=", Carbon::now()->format('Y-m-d'))->value('week_number');

            UserGame::create(['user_id' => auth()->user()->id, 'package_id' => $package->id, 'games' => $games, 'status' => 'active']);

            DB::table('3nfl_user_games')
                ->updateOrInsert(
                    ['user_id' => auth()->user()->id, 'season_id' => $season_id, 'week_number' => $week_no],
                    ['games' => $games]
                );

            $request->user()->newSubscription($package->name, $package->subscription_id)->create($request->input('token'));
        }

        $redirectTo = 'home';
        if (auth()->user()->hasRole('admin'))
            $redirectTo = 'packages.index';

        return redirect()->route($redirectTo)
                ->with('success', 'You have successfully subscribed for the Package ' . $package->name . ' for $' . number_format($request->price / 100, 2));

    }

    public function importPrices(Request $request)
    {
        $validatedData = $request->validate([
            'file' => 'required|mimes:csv',
        ]);
        Excel::import(new PricesImport,$request->file('file'));

        return redirect()->route('packages.index')->with('success', 'CSV file has been imported successfully!');
    }

    public function sortDataTable(Request $request)
    {
        $packages = Package::all();

        foreach ($packages as $package) {
            $package->timestamps = false;
            $id = $package->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $package->update(['order' => $order['position']]);
                }
            }
        }
        return response('Sequence Update Successfully.', 200);
    }
}
