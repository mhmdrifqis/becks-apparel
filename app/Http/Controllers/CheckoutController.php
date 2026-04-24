<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->cartItems()->with(['package', 'material']);
        
        if ($request->has('items') && is_array($request->items)) {
            $query->whereIn('id', $request->items);
        }

        $cartItems = $query->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Silakan pilih produk untuk di-checkout.');
        }

        $upgrades = \App\Models\Upgrade::all()->groupBy('category');
        $userDesigns = \App\Models\Design::where('user_id', Auth::id())->whereNotNull('name')->latest()->get();

        return view('customer.checkout.index', compact('cartItems', 'upgrades', 'userDesigns'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        
        // 1. Validate
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
            'roster' => 'required|array',
            'designs' => 'nullable|array'
        ]);

        $allUpgrades = \App\Models\Upgrade::all()->pluck('price', 'id');

        // 2. Fetch relevant cart items
        $cartItemIds = array_keys($request->roster);
        $cartItems = $user->cartItems()->whereIn('id', $cartItemIds)->with(['package', 'material'])->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Silakan pilih produk.');
        }

        // 3. Create the Order
        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'status' => 'pending',
            'total_amount' => 0, 
        ]);

        $grandTotal = 0;

        // 4. Create Order Items
        foreach ($cartItems as $item) {
            $inputs = $request->roster[$item->id] ?? [];
            
            // Format roster and calculate item surcharge
            $rosterData = [];
            $itemSurcharge = 0;

            foreach ($inputs as $idx => $player) {
                // Surcharge per player in roster (Size + Upgrades)
                $size = $player['size'] ?? 'L';
                $playerSurcharge = 0;
                
                // Size surcharges
                if ($size === 'XXL') $playerSurcharge += 5000;
                if ($size === 'XXXL') $playerSurcharge += 10000;

                // Player Upgrades (Lengan Panjang, Logo, etc)
                $playerUpgradeIds = $player['upgrades'] ?? [];
                $playerUpgradeNames = [];
                if (is_array($playerUpgradeIds)) {
                    foreach ($playerUpgradeIds as $uId) {
                        $price = $allUpgrades->get($uId) ?? 0;
                        $playerSurcharge += $price;
                        
                        // We can also store the names for reference
                        $uModel = \App\Models\Upgrade::find($uId);
                        if ($uModel) $playerUpgradeNames[] = $uModel->name;
                    }
                }

                $rosterData[] = [
                    'ref_name' => $player['ref_name'] ?? '',
                    'name' => $player['name'] ?? '',
                    'number' => $player['number'] ?? '',
                    'size' => $size,
                    'upgrades' => $playerUpgradeIds,
                    'upgrade_names' => $playerUpgradeNames,
                    'surcharge' => $playerSurcharge
                ];
                $itemSurcharge += $playerSurcharge;
            }

            // In the new flow, all upgrades are per player, but we'll include legacy item upgrades if any
            $itemUpgrades = $request->upgrades[$item->id] ?? [];
            $globalSurcharge = 0;
            if (is_array($itemUpgrades) && count($itemUpgrades) > 0) {
                $selectedUpgrades = \App\Models\Upgrade::whereIn('id', $itemUpgrades)->get();
                $globalSurcharge = ($selectedUpgrades->sum('price') * $item->quantity);
            }

            $totalSurcharge = $itemSurcharge + $globalSurcharge;
            $baseProductPrice = $item->package->base_price + ($item->material?->additional_price ?? 0);
            $subtotal = ($baseProductPrice * $item->quantity) + $totalSurcharge;
            $grandTotal += $subtotal;

            $orderItem = \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'package_id' => $item->package_id,
                'material_id' => $item->material_id,
                'quantity' => $item->quantity,
                'roster' => $rosterData,
                'size_surcharge' => $totalSurcharge,
                'subtotal' => $subtotal
            ]);

            // Sync pivot upgrades (for legacy/summary support)
            if (is_array($itemUpgrades) && count($itemUpgrades) > 0) {
                $orderItem->upgrades()->sync($itemUpgrades);
            }
            
            // 5. Handle design
            $designData = $request->designs[$item->id] ?? null;
            if ($designData) {
                // Option A: Saved Design from Customizer
                if (isset($designData['saved_id']) && !empty($designData['saved_id'])) {
                    $orderItem->update(['design_id' => $designData['saved_id']]);
                } 
                // Option B: New Uploaded Files
                else if ($request->hasFile("designs.{$item->id}.files")) {
                    $filePaths = [];
                    $files = $request->file("designs.{$item->id}.files");
                    foreach ($files as $file) {
                        $path = $file->store('orders/designs', 'public');
                        $filePaths[] = $path;
                    }

                    if (count($filePaths) > 0) {
                        $design = \App\Models\Design::create([
                            'user_id' => $user->id,
                            'name' => 'Upload Desain #' . $order->order_number,
                            'preview_path' => $filePaths[0], // Gunakan file pertama sebagai preview
                            'design_json' => ['type' => 'upload', 'files' => $filePaths]
                        ]);
                        $orderItem->update(['design_id' => $design->id]);
                    }
                }
            }
        }

        // 6. Finalize Order total
        $order->update(['total_amount' => $grandTotal]);

        // 7. Cleanup Cart
        // (Do not delete if you want user to be able to go back, but usually we delete)
        $user->cartItems()->whereIn('id', $cartItemIds)->delete();

        return redirect()->route('customer.orders.show', $order->order_number)->with('success', 'Pesanan berhasil dikonfirmasi! Silakan tinjau dan lakukan pembayaran.');
    }
}
