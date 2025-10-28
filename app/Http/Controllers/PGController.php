<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PGController extends Controller
{
    //Payment Gateway Notification Handlers

    public function notifications(Request $request)
    {
        // Handle successful payment notification
        // Validate the request that it's coming from the payment gateway
        // Update the payment status in the database
        // Send confirmation email to the user

        Log::info('Payment Success Notification:', $request->all());

        return response()->json(['status' => 'success'], 200);
    }


}
