<?php

namespace App\Http\Controllers\Api;

use App\Models\Expedition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class DeliveryCharge extends Controller
{
    public function getProvince(Request $request)
    {
        $cacheProvince = Cache::get('province');

        if ($cacheProvince) {
            // Menggunakan data $users dari cache
            $data = $cacheProvince;
        } else {
            // Data tidak ditemukan di cache
            $response  = Http::withHeaders([
                'key' => env('RAJAONGKIR_API_KEY'),
                'Accept' => 'application/json',
            ])->get(env('RAJAONGKIR_API_URL') . '/province');
            
            $data = $response->json();
            $data = $data['rajaongkir']['results'];

            Cache::put('province', $data, 30 * 24 * 60 * 60);
        }

        return response()->json([
            'error' => false,   
            'data' => $data
        ], 200);
    }

    public function getProvinceById(Request $request, $id)
    {
        $response  = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
            'Accept' => 'application/json',
        ])->get(env('RAJAONGKIR_API_URL') . '/province?id=' . $id);
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results']
        ], 200);
    }

    public function getCity(Request $request, $id)
    {
        $response  = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
            'Accept' => 'application/json',
        ])->get(env('RAJAONGKIR_API_URL') . '/city?province=' . $id);
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results']
        ], 200);
    }

    public function getCityById(Request $request, $id, $city_id)
    {
        $response  = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
            'Accept' => 'application/json',
        ])->get(env('RAJAONGKIR_API_URL') . '/city?province=' . $id . '&id=' . $city_id);
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results']
        ], 200);
    }

    public function getCost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required',
            'courier' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors()
            ], 400);
        }

        $response  = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
            'Accept' => 'application/json',
        ])->post(env('RAJAONGKIR_API_URL') . '/cost', [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ]);
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results'][0]
        ], 200);
    }

    public function getExpedition(Request $request)
    {
        $expedition = Expedition::all();

        return response()->json([
            'error' => false,
            'data' => $expedition
        ], 200);
    }
}
