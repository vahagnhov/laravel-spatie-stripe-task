<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPurchaseFormRequest;
use App\Jobs\SendPurchaseProductEmailJob;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;

class ProductController extends Controller
{
    private $productRepository;
    private $userRepository;

    public function __construct(
        UserRepositoryInterface    $userRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Product $product): View
    {
        $this->authorize('index', $product);

        $products = $this->productRepository->getProducts();

        return view("products.index", compact("products"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $this->authorize('show', $product);

        $intent = auth()->user()->createSetupIntent();

        return view("products.purchase", compact("product", "intent"));
    }


    public function purchase(Request $request, ProductPurchaseFormRequest $purchaseFormRequest): RedirectResponse
    {
        $product = $this->productRepository->findData($request->product);

        $this->authorize('purchase', $product);

        try {
            $purchaseFormRequest->validated();
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $details = [
            'user_name' => $request->name,
            'email' => $request->user()->email,
        ];

        try {
            $request->user()->newSubscription($request->product, $product->stripe_product)
                ->create($request->token);
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'purchase.create',
                [$exception->payment->id, 'redirect' => route('home')]
            );
        } catch (CardException $e) {
            // Handle card-related errors (e.g., invalid card number, insufficient funds).
            return redirect()->back()->with('error', $e->getMessage());
        } catch (InvalidRequestException $e) {
            // Handle invalid request errors (e.g., invalid parameters).
            $errorMessage = trans('product/messages.purchased_error');
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Exception $e) {
            // Handle other unexpected exceptions.
            $errorMessage = trans('product/messages.purchased_other_error');
            return redirect()->back()->with('error', $errorMessage);
        }


        try {
            dispatch(new SendPurchaseProductEmailJob($details));
        } catch (\Throwable $exception) {
            Log::error($exception);
        }

        $this->userRepository->assignUserRole($request, $product->product_type);

        $successMessage = trans('product/messages.purchased_success');
        return redirect()->route('home')->with('success', $successMessage);
    }
}
