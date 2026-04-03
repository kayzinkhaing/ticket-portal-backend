<?php

namespace App\Http\Controllers\Web;

use App\Models\Payment;
use App\Services\paypals;
use App\Services\stripes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController
{
    public function showPaymentForm()
    {
        return view('payment.select_gateway');
    }

    public function processPayment(Request $request)
    {
        $gateway = $request->input('gateway');
        $paymentDetails = [];

        if ($gateway == 'paypal') {
            $paymentService = new paypals();

            // Charge via PayPal (example)
            $amount = 100.00; // You can calculate this dynamically
            $paymentDetails = $paymentService->charge($amount, $paymentDetails);

            if (isset($paymentDetails['error'])) {
                return redirect()->route('payment.select')->withErrors(['message' => $paymentDetails['error']]);
            }

            // Redirect to PayPal approval URL
            return redirect()->away($paymentDetails['approval_url']);
        }

        if ($gateway == 'stripe') {
            $paymentService = new stripes();

            // Get the payment method ID from Stripe's form
            $paymentDetails = [
                'payment_method_id' => $request->input('payment_method_id'), // Replace with actual payment method ID
                'return_url' => 'http://localhost:8000/payment-success'
            ];
            $amount = 100.00; // You can calculate this dynamically
            // Charge via Stripe (example)
            $paymentDetails = $paymentService->charge($amount, $paymentDetails);
            if (isset($paymentDetails['error'])) {
                return redirect()->route('payment.select')->withErrors(['message' => $paymentDetails['error']]);
            }

            // Handle successful payment (you can redirect to a success page)
            return redirect()->route('payment.success');
        }

        return redirect()->route('payment.select')->withErrors(['message' => 'Invalid payment method selected']);
    }

    // Method to handle successful PayPal payment
    public function paymentSuccess(Request $request)
    {
        // Get payment details from the query parameters
        $paymentId = $request->get('paymentId');
        $payerId = $request->get('PayerID');

        // Ensure both paymentId and payerId are present
        if (empty($paymentId) || empty($payerId)) {
            return redirect()->route('payment.select')->withErrors(['message' => 'Payment failed or canceled.']);
        }

        // You could call the PayPal service here, but since charge() already creates and executes,
        // we just show a success message.
        // Example: assuming 'charge' was successful earlier, you just want to confirm it.
        // Store payment details in the database (you can modify this as needed)
        Payment::create([
            'payment_method' => 'paypal',
            'transaction_id' => $paymentId,
            'amount' => 1000, // Assuming 'amount' is returned by the service
            'currency' => 'USD', // You can make this dynamic if needed
            'status' => 'completed',
            'user_id' => Auth::user()->id, // Assuming the user is authenticated
        ]);

        return view('payment.success');
    }
    // Method to handle PayPal cancel
    public function paymentCancel(Request $request)
    {
        // This will be called if the user cancels the payment
        return view('payment.cancel');
    }
}
