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

        $materials = \App\Models\Material::whereJsonContains('allowed_categories', $package->category)
            ->orWhereNull('allowed_categories') // Fallback temporarily until the admin updates the materials
            ->get();

        return view('catalog.show', compact('package', 'materials'));
    }
}
