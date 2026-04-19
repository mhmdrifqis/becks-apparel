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
        $cartItems = Auth::user()->cartItems()->with('package')->get();
        return view('customer.cart.index', compact('cartItems'));
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
        
        $cartItem = $user->cartItems()->where('package_id', $package->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            $user->cartItems()->create([
                'package_id' => $package->id,
                'quantity' => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'counts' => $this->getCountsData()
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
}
