<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Constants\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user(); // Get the currently authenticated user

        if ($user->hasRole(Roles::B2C_CUSTOMER)) {
            $purchaseDetails = 'Last 4 digits of B2C card number: XXXX';
        } elseif ($user->hasRole(Roles::B2B_CUSTOMER)) {
            $purchaseDetails = 'Last 4 digits of B2B card number: XXXX';
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
