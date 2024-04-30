<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expedition;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DeliveryCharge extends Controller
{
    protected $apiKey = '5561533169cf86d2475d12a55455f7d2';
    protected $apiUrl = 'https://api.rajaongkir.com/starter';

    public function getProvince(Request $request)
    {
        $response  = Http::withHeaders([
            'key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->apiUrl . '/province');
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results']
        ], 200);
    }

    public function getProvinceById(Request $request, $id)
    {
        $response  = Http::withHeaders([
            'key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->apiUrl . '/province?id=' . $id);
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results']
        ], 200);
    }

    public function getCity(Request $request, $id)
    {
        $response  = Http::withHeaders([
            'key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->apiUrl . '/city?province=' . $id);
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results']
        ], 200);
    }

    public function getCityById(Request $request, $id, $city_id)
    {
        $response  = Http::withHeaders([
            'key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get($this->apiUrl . '/city?province=' . $id . '&id=' . $city_id);
        
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
            'key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->post($this->apiUrl . '/cost', [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ]);
        
        $data = $response->json();
        return response()->json([
            'error' => false,
            'data' => $data['rajaongkir']['results']
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
