<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Constants\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the Stripe customer ID associated with the user
        $stripeCustomerId = $user->stripe_id;

        // Retrieve the customer from Stripe
        $stripeCustomer = Cashier::findBillable($stripeCustomerId);

        // Get the default payment method (source) for the customer
        $defaultPaymentMethod = $stripeCustomer->defaultPaymentMethod();

        // Retrieve the card details, including the last 4 digits
        $paymentMethod = $stripeCustomer->findPaymentMethod($defaultPaymentMethod->id);
        $cardDetails = $paymentMethod->card;
        $lastFourDigits = $cardDetails->last4;

        if ($user->hasRole(Roles::B2C_CUSTOMER)) {
            $purchaseDetails = "Last 4 digits of B2C card number: $lastFourDigits";
        } elseif ($user->hasRole(Roles::B2B_CUSTOMER)) {
            $purchaseDetails = "Last 4 digits of B2B card number: $lastFourDigits";
        } else {
            $purchaseDetails = 'No purchase details available';
        }

        $canCancelPurchase = $user->can(Permission::CANCEL_PURCHASE);
        return view('home', compact('user', 'purchaseDetails', 'canCancelPurchase'));
    }

    public function cancelPurchase()
    {
        // Check if the user can cancel the purchase using policies or gates
        if (Auth::user()->can(Permission::CANCEL_PURCHASE)) {
            // Perform cancellation logic here
            // Redirect or return a response as needed
        } else {
            abort(403, 'Unauthorized');
        }
    }
}
