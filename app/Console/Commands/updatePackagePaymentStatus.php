<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Season;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class updatePackagePaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentstatus:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will set the status active="N" for the package once it gets expire.';

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
        $payments = Payment::whereActive('Y')->get();

        foreach ($payments as $payment){
            Payment::whereId($payment->id)
                ->update(['active' => $this->getPackageExpireStatus($payment->package_id, $payment->created_at, $payment->payment_type)]);
        }
        return 0;
    }

    public function getPackageExpireStatus($packageId, $subscriptionDate, $paymentType)
    {
        $subscriptionDate = CarbonImmutable::createFromFormat('Y-m-d H:i:s', $subscriptionDate);
        $weekEndDate = $subscriptionDate->endOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $monthEndDate = $subscriptionDate->endOfMonth()->format('Y-m-d');

        $status = 'Y';
        $season = Season::orderByDesc('id')->first();

        if(in_array($packageId, [3,4,5,6,7,8])){
            if(\Carbon\Carbon::now()->gt($weekEndDate)){
                $status = 'N';
            }
        }
        elseif(in_array($packageId,[1,2]) && $paymentType == 'subscription'){
            if(\Carbon\Carbon::now()->gt($monthEndDate)){
                $status = 'N';
            }
        }elseif(in_array($packageId,[1,2]) && $paymentType == 'single_payment'){
            if(\Carbon\Carbon::now()->gt($season->end_date)){
                $status = 'N';
            }
        }

        return $status;
    }
}
