<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category', 'images')->get();

        return response()->json($products, 200);
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
        
        // $product = Product::create([
        //     'title' => $request->title,
        //     'slug' => Str::slug($request->title),
        //     'price' => $request->price,
        //     'description' => $request->description,
        //     'category_id' => $request->category_id
        // ]);
        
        // $img = [];
        // foreach ($request->file('images') as $image) {
            // $path = $image->store('images', 'public');
            // $imageUrl = url('storage/' . $path);

            // $product->images()->create([
            //     'url' => $imageUrl
            // ]);
        //     $img[] = $image->getClientOriginalName();
        // }

        if ($request->hasFile('images')){
            $fileImages = $request->file('images')->getClientOriginalName();

            // $files = [];
            // foreach ($fileImages as $image) {
            //     $fileName = $image->getClientOriginalName();
            //     $files[] = $fileName;
            // }

            return response()->json([
                'error' => false,
                'message' => 'Product created successfully.',
                'images' => $fileImages
            ], 201);
        }

    }
}
