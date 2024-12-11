<?php

namespace App\Http\Controllers;

use Stripe;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * create a new instance of the class
     *
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:subscription-cancel', ['only' => ['index','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Subscription::with('user')->orderBy('stripe_status', 'desc')->get();
        $cancel = Subscription::with('user')->whereStripeStatus('inactive')->whereNull('ends_at')->orderBy('stripe_status', 'desc')->get();
        $packages = Package::pluck('name','subscription_id')->toArray();

        return view('subscriptions.index')->with(['data'=>$data, 'cancel'=>$cancel, 'packages'=>$packages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        Subscription::whereStripePrice($id)->whereUserId(auth()->user()->id)
            ->update(['stripe_status' => 'inactive']);

        return redirect()->back()->with('success','Your request to Cancel Subscription been Sent to Admin Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

        $stripe->subscriptions->cancel(
            $id,
            []
        );

        Subscription::whereStripeId($id)
            ->update(['stripe_status' => 'inactive', 'ends_at'=>date('Y-m-d H:i:s')]);

        return redirect()->back()->with('success','Subscription cancelled successfully.');
    }
}
