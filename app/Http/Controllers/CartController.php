<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with(['package', 'material'])->get();
        $materials = \App\Models\Material::all();
        return view('customer.cart.index', compact('cartItems', 'materials'));
    }

    /**
     * Add package to cart (AJAX)
     */
    public function add(Request $request, Package $package)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login first'], 401);
        }

        $user = Auth::user();
        $materialId = $request->input('material_id');
        $quantity = $request->input('quantity', 1);
        
        $cartItem = $user->cartItems()
            ->where('package_id', $package->id)
            ->where('material_id', $materialId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cartItem = $user->cartItems()->create([
                'package_id' => $package->id,
                'material_id' => $materialId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'counts' => $this->getCountsData(),
            'cart_item_id' => $cartItem ? $cartItem->id : null
        ]);
    }

    /**
     * Get real-time counts for indicators (AJAX)
     */
    public function getCounts()
    {
        return response()->json($this->getCountsData());
    }

    /**
     * Private helper to aggregate counts
     */
    private function getCountsData()
    {
        if (!Auth::check()) {
            return ['cart' => 0, 'orders' => 0, 'notifications' => 0];
        }

        $user = Auth::user();

        return [
            'cart' => $user->cartItems()->sum('quantity'),
            'orders' => $user->activeOrdersCount(),
            'notifications' => $user->unreadNotifications()->count(),
        ];
    }

    /**
     * Update item quantity in cart
     */
    public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $action = $request->input('action');
        
        // If JS sends an explicit quantity, use it. Otherwise, increment/decrement.
        if ($request->has('quantity')) {
            $quantity = (int)$request->input('quantity');
        } else {
            $quantity = $cartItem->quantity;
            if ($action === 'increase') {
                $quantity++;
            } elseif ($action === 'decrease') {
                $quantity--;
            }
        }

        if ($quantity < 1) {
            $quantity = 1;
        }

        $data = ['quantity' => $quantity];
        if ($request->has('material_id')) {
            $data['material_id'] = $request->input('material_id');
        }

        $cartItem->update($data);

        if ($request->expectsJson()) {
            // Reload package and material for accuracy
            $cartItem->load(['package', 'material']);
            $unitPrice = (float) $cartItem->package->base_price + (float) ($cartItem->material ? $cartItem->material->additional_price : 0);
            
            return response()->json([
                'success' => true,
                'quantity' => $cartItem->quantity,
                'price' => $unitPrice,
                'material_id' => $cartItem->material_id,
                'material_name' => $cartItem->material->name ?? '-'
            ]);
        }

        return redirect()->back()->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Remove item from cart
     */
    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
