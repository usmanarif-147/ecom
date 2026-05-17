<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->paginatePublic($request);
        return ProductResource::collection($products);
    }

    public function show(int $id)
    {
        $product = Product::active()
            ->with(['category', 'sizes', 'colors', 'images' => fn($q) => $q->orderBy('id', 'asc')])
            ->findOrFail($id);

        $product->increment('views');

        return new ProductDetailResource($product);
    }
}
