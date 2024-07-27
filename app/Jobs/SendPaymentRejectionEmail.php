<?php

namespace App\Jobs;

use Throwable;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use App\Notifications\PaymentRejected;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendPaymentRejectionEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Payment $payment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try 
        {
            
            $user = User::find($this->payment->user_id);
            
            $user->notify(new PaymentRejected( $this->payment ));

        }
        catch(Throwable $e )
        {
            logger($e->getMessage());
            report($e);
        }
    }
}
