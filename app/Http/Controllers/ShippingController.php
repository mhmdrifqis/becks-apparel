<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    private $apiKey;
    private $baseUrl = 'https://rajaongkir.komerce.id/api/v1';

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
    }

    public function getProvinces()
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("{$this->baseUrl}/destination/province");

            $data = $response->json();
            
            if (isset($data['meta']['code']) && $data['meta']['code'] !== 200) {
                return response()->json(['error' => $data['meta']['message'] ?? 'Error'], 400);
            }

            // Map data to frontend format
            $provinces = [];
            foreach ($data['data'] ?? [] as $prov) {
                $provinces[] = [
                    'province_id' => $prov['id'] ?? null,
                    'province' => $prov['name'] ?? null,
                ];
            }

            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCities($provinceId)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get("{$this->baseUrl}/destination/city/{$provinceId}");

            $data = $response->json();

            if (isset($data['meta']['code']) && $data['meta']['code'] !== 200) {
                return response()->json(['error' => $data['meta']['message'] ?? 'Error'], 400);
            }

            // Map data to frontend format
            $cities = [];
            foreach ($data['data'] ?? [] as $city) {
                $cities[] = [
                    'city_id' => $city['id'] ?? null,
                    'city_name' => $city['name'] ?? null,
                    'type' => 'Kota', // Provide a default type so frontend renders cleanly
                ];
            }

            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function calculateCost(Request $request)
    {
        $request->validate([
            'destination' => 'required|numeric',
            'weight' => 'required|numeric', // in grams
            'courier' => 'required|string|in:jne,pos,tiki,sicepat,jnt,ninja,lion,anteraja,ide'
        ]);

        try {
            $origin = env('RAJAONGKIR_ORIGIN_CITY_ID', 456); // Default 456 (Tangerang) jika tidak diset

            $response = Http::asForm()->withHeaders([
                'key' => $this->apiKey
            ])->post("{$this->baseUrl}/calculate/domestic-cost", [
                'origin' => $origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier
            ]);

            $data = $response->json();

            if (isset($data['meta']['code']) && $data['meta']['code'] !== 200) {
                return response()->json(['error' => $data['meta']['message'] ?? 'Error'], 400);
            }

            // Extract the raw costs array
            $rawCosts = $data['data'][0]['costs'] ?? $data['data'] ?? [];

            // Map data to frontend format (RajaOngkir standard nested array)
            $formattedCosts = [];
            foreach ($rawCosts as $item) {
                // Determine the cost value
                $value = 0;
                if (isset($item['cost'][0]['value'])) {
                    $value = $item['cost'][0]['value'];
                } elseif (isset($item['cost']['value'])) {
                    $value = $item['cost']['value'];
                } elseif (isset($item['cost']) && is_numeric($item['cost'])) {
                    $value = $item['cost'];
                }

                // Determine the ETD
                $etd = '';
                if (isset($item['cost'][0]['etd'])) {
                    $etd = $item['cost'][0]['etd'];
                } elseif (isset($item['cost']['etd'])) {
                    $etd = $item['cost']['etd'];
                } elseif (isset($item['etd'])) {
                    $etd = $item['etd'];
                }

                $formattedCosts[] = [
                    'service' => $item['service'] ?? 'REG',
                    'description' => $item['description'] ?? '',
                    'cost' => [
                        [
                            'value' => (int) $value,
                            'etd' => $etd,
                            'note' => ''
                        ]
                    ]
                ];
            }

            return response()->json($formattedCosts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
