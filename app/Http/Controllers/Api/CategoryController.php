<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'required'
        ]);

        if($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'The given data was invalid.'. $validate->errors(),
            ], 422);
        }

        $path = $request->file('image')->store('images', 'public');

        $imageUrl = url('storage/' . $path);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imageUrl
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Category created successfully.',
        ], 201);
    }
}
