<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Constants\Roles;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $lastFourDigits = '';
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the Stripe customer ID associated with the user
        $stripeCustomerId = $user->stripe_id;

        // Retrieve the customer from Stripe
        $stripeCustomer = Cashier::findBillable($stripeCustomerId);

        // Get the default payment method (source) for the customer
        if ($stripeCustomer) {
            $defaultPaymentMethod = $stripeCustomer->defaultPaymentMethod();
            // Retrieve the card details, including the last 4 digits
            $paymentMethod = $stripeCustomer->findPaymentMethod($defaultPaymentMethod->id);
            $cardDetails = $paymentMethod->card;
            $lastFourDigits = $cardDetails->last4;
        }

        $lastFourDigitsOfText = trans('dashboard/messages.last_four_digits_of');
        $cardNumberText = trans('dashboard/messages.card_number');

        if ($user->hasRole(Roles::B2C_CUSTOMER) && $lastFourDigits) {
            $purchaseDetails = "$lastFourDigitsOfText B2C $cardNumberText: $lastFourDigits";
        } elseif ($user->hasRole(Roles::B2B_CUSTOMER) && $lastFourDigits) {
            $purchaseDetails = "$lastFourDigitsOfText B2B $cardNumberText: $lastFourDigits";
        } else {
            $purchaseDetails = trans('dashboard/messages.no_purchase');
        }

        $canCancelPurchase = $user->can(Permission::CANCEL_PURCHASE);
        return view('dashboard.index', compact('user', 'purchaseDetails', 'canCancelPurchase'));
    }

    public function cancelPurchase()
    {
        // Check if the user can cancel the purchase using policies or gates
        if (Auth::user()->can(Permission::CANCEL_PURCHASE)) {
            // Perform cancellation logic here
            // Redirect or return a response as needed
        } else {
            abort(403, trans('dashboard/messages.unauthorized'));
        }
    }
}
