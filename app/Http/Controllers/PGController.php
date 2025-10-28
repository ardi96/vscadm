<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PGController extends Controller
{
    //Payment Gateway Notification Handlers

    public function notifySuccess(Request $request)
    {
        // Handle successful payment notification
        // Validate the request that it's coming from the payment gateway
        // Update the payment status in the database
        // Send confirmation email to the user

        Log::info('Payment Success Notification:', $request->all());

        return response()->json(['status' => 'success'], 200);
    }

    public function notifyFailure(Request $request)
    {
        // Handle failed payment notification
        // Validate the request that it's coming from the payment gateway
        // Update the payment status in the database
        // Notify the user about the failure

        Log::info('Payment Failure Notification:', $request->all());

        return response()->json(['status' => 'success'], 200);
    }

    public function notifyOther(Request $request)
    {
        // Handle canceled payment notification
        // Validate the request that it's coming from the payment gateway
        // Update the payment status in the database

        Log::info('Payment Other Notification:', $request->all());
        
        return response()->json(['status' => 'success'], 200);
    }

}
