<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SingleProductResource;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('offset') && $request->has('limit')) {
            if ($request->category == null) {
                $products = Product::with('category', 'images')
                    ->offset($request->offset)
                    ->limit($request->limit)
                    ->get();
            }else{
                $products = Product::with('category', 'images')
                    ->where('category_id', Category::where('slug', $request->category)->first()->id)
                    ->offset($request->offset)
                    ->limit($request->limit)
                    ->get();
            }
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

    public function singgle($id)
    {
        $product = Product::with('category', 'images')->find($id);

        if (!$product) {
            return response()->json([
                'error' => true,
                'message' => 'Product not found.'
            ], 404);
        }

        if ($product->additional) {
            $product->with('addition');
        }

        $productResource = new SingleProductResource($product);
        
        return response()->json($productResource, 200);
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
        
        if($request->hasFile('images')){
            foreach($request->file('images') as $image){
                $path = $image->store('images', 'public');
                $imageUrl = url('storage/' . $path);
                
                $product->images()->create([
                    'url' => $imageUrl
                ]);
            }
        }else{
            foreach($request->images as $image){
                $product->images()->create([
                    'url' => $image
                ]);
            }
        }

        if ($request->has('additional')) {
            $product->addition()->create([
                'additional' => $request->additional
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Product created successfully.'
        ], 201);
    }
}
