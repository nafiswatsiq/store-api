<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function order(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'address_id' => 'required',
            'subtotal' => 'required',
            'shipping' => 'required',
            'expedition' => 'required',
            'estimation' => 'required',
            'total' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ], 400);
        }
        
        $order = Order::create([
            'user_id' => auth()->user()->id,
            'invoice' => 'INV/' . time(), // 'INV/' . time() . '/' . auth()->user()->id,
            'status' => 'pending',
            'address_id' => $request->address_id,
            'subtotal' => $request->subtotal,
            'shipping' => $request->shipping,
            'expedition' => $request->expedition,
            'estimation' => $request->estimation,
            'total' => $request->total,
        ]);

        $carts = Cart::where('user_id', auth()->user()->id)
                    ->where('is_checked_out', false)->get();

        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'cart_id' => $cart->id
            ]);

            $cart->update([
                'is_checked_out' => true
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Order created successfully.'
        ], 201);
    }

    public function getOrder()
    {
        $orders = Order::with('address','items.cart.product.images')->where('user_id', auth()->user()->id)->get();

        $orderCollection = OrderResource::collection($orders);

        return response()->json([
            'error' => false,
            'orders' => $orderCollection
        ], 200);}
}
