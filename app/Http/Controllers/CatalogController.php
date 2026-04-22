<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Package;

class CatalogController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)
            ->get()
            ->groupBy('category');

        return view('catalog.index', compact('packages'));
    }

    public function show($slug)
    {
        $package = Package::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $materials = \App\Models\Material::all();

        return view('catalog.show', compact('package', 'materials'));
    }
}
