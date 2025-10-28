<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Leave;
use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use App\Models\GlobalParameter;
use Illuminate\Support\Facades\DB;

class MidtransService
{
    // Midtrans related services can be implemented here


    /**
     * Checkout process using Midtrans
     * IS: user has selected items and ready to checkout
     * FS: transaction is created in Midtrans and payment URL is returned
     * @param Member $member
     * @param array $transactionDetails 
     * @param array $itemDetails
     * @param array $customerDetails
     * @return string $paymentUrl   
     */
    public static function checkout(Member $member, array $transactionDetails, array $itemDetails, array $customerDetails)
    {
        // Implement Midtrans checkout process here
        
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);   
        Config::$isSanitized = true;
        
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;
        
        $params = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        $snapToken = Snap::getSnapToken( $params );

        $paymentUrl = Snap::getSnapUrl( $params );

        return $paymentUrl;
    }

}