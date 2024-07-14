<?php

namespace App\Jobs;

use Throwable;
use App\Models\User;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use App\Notifications\InvoiceAvailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class SendInvoiceMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Invoice $invoice)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try 
        {
            // $invoice = InvoiceService::generate($this->member);
            
            $user = User::find($this->invoice->parent_id);
            
            $user->notify(new InvoiceAvailable( $this->invoice ));

        }
        catch(Throwable $e )
        {
            report($e);
        }
    }

}