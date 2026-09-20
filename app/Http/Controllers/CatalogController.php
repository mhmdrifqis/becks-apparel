<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Package;

class CatalogController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)
            ->withAvg(['reviews' => function ($query) {
                $query->where('is_visible', true);
            }], 'rating')
            ->withSum(['orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready', 'shipped', 'completed']);
                });
            }], 'quantity')
            ->get()
            ->groupBy('category');

        return view('catalog.index', compact('packages'));
    }

    public function show($slug)
    {
        $package = Package::where('slug', $slug)
            ->where('is_active', true)
            ->withSum(['orderItems' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready', 'shipped', 'completed']);
                });
            }], 'quantity')
            ->firstOrFail();

        // Filter bahan sesuai kategori produk (jersey, jaket, kaos, kemeja)
        $productType = strtolower($package->category);
        $materialsQuery = \App\Models\Material::forProductType($productType);

        if ($productType === 'tshirt' || $productType === 'kaos') {
            if (stripos($package->name, '24s') !== false) {
                $materialsQuery->where('name', 'like', '%24s%');
            } elseif (stripos($package->name, '30s') !== false) {
                $materialsQuery->where('name', 'like', '%30s%');
            }
        }
        $materials = $materialsQuery->get();

        return view('catalog.show', compact('package', 'materials'));
    }
}
