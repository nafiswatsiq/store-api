<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use Termwind\Components\Li;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function getLikes()
    {
        $likes = auth()->user()->likes->map(function ($like) {
            return [
                'id' => $like->product->id,
                'title' => $like->product->title,
                'price' => $like->product->price,
                'image' => $like->product->images->first()->url
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $likes
        ]);
    }

    public function addLikes(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()
            ], 400);
        }

        $like = Like::where('user_id', auth()->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($like) {
            $like->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from likes'
            ]);
        }

        Like::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to likes'
        ]);
    }
}
