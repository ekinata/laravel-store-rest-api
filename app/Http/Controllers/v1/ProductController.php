<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\Product;
use App\Http\Requests\v1\Product\StoreProductRequest;
use App\Http\Requests\v1\Product\UpdateProductRequest;
use App\Http\Resources\v1\ProductResource;
use App\Http\Resources\v1\ProductCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $limit = request()->query('limit', 30);

        if ($limit > 500) return response()->json(['message' => 'Limit should not exceed 500.'], 400);

        $queries = array_filter(request()->query(), function ($key) {
            return in_array($key, (new Product())->getFillable());
        }, ARRAY_FILTER_USE_KEY);

        if (request()->query('trash')) {
            $products = Product::onlyTrashed()->where($queries)->paginate($limit);
        }else{
            $products = Product::where($queries)->paginate($limit);
        }

        return new ProductCollection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // We don't need this method in API
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        $req = $request->validated();
        if (!isset($req['slug'])) {
            $slug = $this->fixSlug($req['name']);
            $req['slug'] = $slug;
            if (Product::where('slug', $slug)->first()) {
                $req['slug'] = $slug . '-' . time();
            }
        }
        $product = Product::create($req);
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\v1\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // We don't need this method in API
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\v1\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $req = $request->validated();
        if(isset($req['slug'])){
            $req['slug'] = $this->fixSlug($req['slug']);
        }
        $product->update($req);
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\v1\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

    /**
    * Fix slug.
    *
    * @param  string  $rawSlug
    * @return string
    */
    public function fixSlug($rawSlug)
    {
    //fix characters
    $noturl = array('ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ş', 'Ş', 'ö', 'Ö', 'ç', 'Ç');
    $url = array('i', 'i', 'g', 'g', 'u', 'u', 's', 's', 'o', 'o', 'c', 'c');
    //slug is not provided, so we will generate it from name
    $slug = str_replace(' ', '-', strtolower(str_replace($noturl, $url, $rawSlug)));
    $slug = htmlspecialchars($slug);
    return $slug;
    }
}
