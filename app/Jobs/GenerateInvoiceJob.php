<?php

namespace App\Jobs;

use Throwable;
use App\Models\User;
use App\Models\Member;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use App\Notifications\InvoiceAvailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class GenerateInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Member $member)
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();

        try 
        {
            $invoice = InvoiceService::generate($this->member);
            
            $user = User::find($this->member->parent_id);
            
            $user->notify(new InvoiceAvailable());

            DB::commit();
        }
        catch(Throwable $e )
        {
            report($e);
            DB::rollBack();
        }
    }

}