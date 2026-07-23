<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
            'nama_penerima' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:30',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
        ]);

        $userId = Auth::id();
        $isDefault = $request->boolean('is_default');

        // If user has no existing addresses, force the first address to be default
        if (UserAddress::where('user_id', $userId)->count() === 0) {
            $isDefault = true;
        }

        // Only 1 default address per user
        if ($isDefault) {
            UserAddress::where('user_id', $userId)->update(['is_default' => false]);
        }

        $address = UserAddress::create([
            'user_id' => $userId,
            'label' => $request->filled('label') ? $request->label : 'Alamat',
            'nama_penerima' => $request->nama_penerima,
            'no_telepon' => $request->no_telepon,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'is_default' => $isDefault,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Alamat berhasil disimpan!',
                'address' => $address,
                'addresses' => Auth::user()->addresses()->latest()->get()
            ]);
        }

        return redirect()->back()->with('success', 'Alamat berhasil disimpan!');
    }

    public function setDefault(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat utama berhasil diperbarui.',
            'addresses' => Auth::user()->addresses()->latest()->get()
        ]);
    }

    public function destroy(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        // If the deleted address was default, make the latest remaining address default
        if ($wasDefault) {
            $nextAddress = UserAddress::where('user_id', Auth::id())->latest()->first();
            if ($nextAddress) {
                $nextAddress->update(['is_default' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus.',
            'addresses' => Auth::user()->addresses()->latest()->get()
        ]);
    }
}
