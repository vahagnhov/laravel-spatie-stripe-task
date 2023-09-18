<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

        return view("products", compact("products"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $this->authorize('show', $product);

        $intent = auth()->user()->createSetupIntent();

        return view("purchase", compact("product", "intent"));
    }

    public function purchase(Request $request): View
    {
        $this->authorize('purchase', $request->product);

        $product = $this->productRepository->findData($request->product);

        $request->user()->newSubscription($request->product, $product->stripe_product)
            ->create($request->token);

        $this->userRepository->assignUserRole($request, $product->product_type);

        return view("purchase_success");
    }
}
