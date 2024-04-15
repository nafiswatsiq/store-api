<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('offset') && $request->has('limit')) {
            $products = Product::with('category', 'images')
                ->offset($request->offset)
                ->limit($request->limit)
                ->get();
        } else if($request->has('limit')) {
            $products = Product::with('category', 'images')
                ->limit($request->limit)
                ->get();
        } else {
            $products = Product::with('category', 'images')->get();
        }

        $productsCollection = ProductResource::collection($products);

        return response()->json($productsCollection, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'title' => 'required',
            'price' => 'required',
            'description' => 'required',
            'category_id' => 'required',
        ]);

        if($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'The given data was invalid.'. $validate->errors(),
            ], 422);
        }
        
        $product = Product::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'price' => $request->price,
            'description' => $request->description,
            'category_id' => $request->category_id
        ]);
        
        foreach ($request->file('images') as $image) {
            $path = $image->store('images', 'public');
            $imageUrl = url('storage/' . $path);

            $product->images()->create([
                'url' => $imageUrl
            ]);
        }

        if ($request->has('additional')) {
            $product->additional()->create([
                'additional' => $request->additional
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Product created successfully.'
        ], 201);
    }
}
