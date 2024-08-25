<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\v1\Category;
use App\Http\Requests\v1\Category\StoreCategoryRequest;
use App\Http\Requests\v1\Category\UpdateCategoryRequest;
use App\Http\Resources\v1\CategoryResource;
use App\Http\Resources\v1\CategoryCollection;

class CategoryController extends Controller
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
            return in_array($key, (new Category())->getFillable());
        }, ARRAY_FILTER_USE_KEY);

        if (request()->query('trash')) {
            $categories = Category::onlyTrashed()->where($queries)->paginate($limit);
        }else{
            $categories = Category::where($queries)->paginate($limit);
        }
        
        return new CategoryCollection($categories);
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
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $req = $request->validated();
        if (!isset($req['slug'])) {
            $slug = $this->fixSlug($req['name']);
            $req['slug'] = $slug;
            if (Category::where('slug', $slug)->first()) {
                $req['slug'] = $slug . '-' . time();
            }
        }
        $category = Category::create($req);
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\v1\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // We don't need this method in API
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\v1\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $req = $request->validated();
        if(isset($req['slug'])){
            $req['slug'] = $this->fixSlug($req['slug']);
        }
        $category->update($req);
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  \App\Models\v1\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
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
