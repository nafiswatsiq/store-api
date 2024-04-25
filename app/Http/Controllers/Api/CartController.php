<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->user()->id)->get();
        $cart->load('product.images');

        $cartCollection = CartResource::collection($cart);

        return response()->json([
            'error' => false,
            'cart' => $cartCollection
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'size' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ], 400);
        }

        Cart::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'size' => $request->size ?? null
        ]);
        
        return response()->json([
            'error' => false,
            'message' => 'Cart stored'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'error' => false,
            'message' => 'Cart updated'
        ], 200);
    }

    public function destroy($id)
    {
        return response()->json([
            'error' => false,
            'message' => 'Cart deleted'
        ], 200);
    }
}