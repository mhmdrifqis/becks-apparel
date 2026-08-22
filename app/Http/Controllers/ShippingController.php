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
        $this->apiKey = config('services.rajaongkir.api_key', env('RAJAONGKIR_API_KEY'));
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

    public function autoCalculate(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'weight' => 'required|numeric|min:1',
            'courier' => 'nullable|string'
        ]);

        $destination = $request->destination;
        $weight = max(1, (int) $request->weight);
        $origin = env('RAJAONGKIR_ORIGIN_CITY_ID', 456);
        $targetCourier = $request->input('courier');

        $cacheKey = "shipping_auto_{$origin}_{$destination}_{$weight}_" . ($targetCourier ?: 'all');

        try {
            $result = \Illuminate\Support\Facades\Cache::remember($cacheKey, 600, function () use ($origin, $destination, $weight, $targetCourier) {
                $couriers = $targetCourier ? [$targetCourier] : ['jne', 'pos', 'tiki'];
                $allOptions = [];

                foreach ($couriers as $courierCode) {
                    try {
                        $response = Http::asForm()->withHeaders([
                            'key' => $this->apiKey
                        ])->post("{$this->baseUrl}/calculate/domestic-cost", [
                            'origin' => $origin,
                            'destination' => $destination,
                            'weight' => $weight,
                            'courier' => $courierCode
                        ]);

                        $data = $response->json();
                        if (!isset($data['meta']['code']) || $data['meta']['code'] === 200) {
                            $rawCosts = $data['data'][0]['costs'] ?? $data['data'] ?? [];
                            foreach ($rawCosts as $item) {
                                $val = $item['cost'][0]['value'] ?? $item['cost']['value'] ?? (is_numeric($item['cost']) ? $item['cost'] : 0);
                                $etd = $item['cost'][0]['etd'] ?? $item['cost']['etd'] ?? $item['etd'] ?? '';
                                
                                if ($val > 0) {
                                    $allOptions[] = [
                                        'courier' => $courierCode,
                                        'courier_name' => strtoupper($courierCode),
                                        'service' => $item['service'] ?? 'REG',
                                        'description' => $item['description'] ?? '',
                                        'cost' => (int) $val,
                                        'etd' => $etd,
                                    ];
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                if (empty($allOptions)) {
                    return null;
                }

                usort($allOptions, fn($a, $b) => $a['cost'] <=> $b['cost']);

                return [
                    'cheapest' => $allOptions[0],
                    'all_options' => $allOptions
                ];
            });

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Layanan pengiriman tidak tersedia untuk kota tujuan ini atau API mengalami gangguan.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'cheapest' => $result['cheapest'],
                'all_options' => $result['all_options']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim: ' . $e->getMessage()
            ], 500);
        }
    }
}
