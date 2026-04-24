<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Auth::user()->designs()->latest()->get();
        return view('customer.designs.index', compact('designs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'design_json' => 'required|string',
            'preview_image' => 'required|string', // Base64 image
        ]);

        $previewPath = null;
        if ($request->preview_image) {
            $imageData = $request->preview_image;
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'design_' . time() . '_' . Str::random(10) . '.png';
            $previewPath = 'designs/' . $imageName;
            Storage::disk('public')->put($previewPath, base64_decode($image));
        }

        Design::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'design_json' => json_decode($request->design_json, true),
            'preview_path' => $previewPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Desain berhasil disimpan!',
            'redirect' => route('customer.designs')
        ]);
    }

    public function update(Request $request, Design $design)
    {
        if ($design->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'design_json' => 'required|string',
            'preview_image' => 'nullable|string',
        ]);

        $previewPath = $design->preview_path;
        if ($request->preview_image) {
            // Delete old image
            if ($previewPath) {
                Storage::disk('public')->delete($previewPath);
            }

            $imageData = $request->preview_image;
            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = 'design_' . time() . '_' . Str::random(10) . '.png';
            $previewPath = 'designs/' . $imageName;
            Storage::disk('public')->put($previewPath, base64_decode($image));
        }

        $design->update([
            'name' => $request->name,
            'design_json' => json_decode($request->design_json, true),
            'preview_path' => $previewPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Desain berhasil diperbarui!',
            'redirect' => route('customer.designs')
        ]);
    }

    public function destroy(Design $design)
    {
        if ($design->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }

        if ($design->preview_path) {
            Storage::disk('public')->delete($design->preview_path);
        }

        $design->delete();

        return back()->with('success', 'Desain berhasil dihapus.');
    }

    public function edit(Design $design)
    {
        if ($design->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customizer', [
            'design' => $design
        ]);
    }
}
