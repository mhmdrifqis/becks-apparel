<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a specific order / invoice for the customer.
     */
    public function show(Order $order)
    {
        // 1. Validasi Akses: Hanya pemilik pesanan yang boleh melihat halamannya
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses tidak sah.');
        }

        // 2. Muat relasi yang diperlukan untuk Invoice
        $order->load(['orderItems.package', 'orderItems.material', 'orderItems.design', 'orderItems.upgrades']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Cancel an unpaid order.
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Pesanan yang sudah dibayar (DP/Lunas) tidak dapat dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders')->with('success', 'Pesanan #' . $order->order_number . ' telah dibatalkan.');
    }

    /**
     * Update roster data for a specific order item.
     */
    public function updateRoster(Request $request, \App\Models\OrderItem $item)
    {
        $order = $item->order;

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Data pemain tidak dapat diubah setelah pembayaran dilakukan.');
        }

        $request->validate([
            'roster' => 'required|array',
            'roster.*.name' => 'nullable|string|max:255',
            'roster.*.number' => 'nullable|string|max:10',
            'roster.*.size' => 'required|string|in:S,M,L,XL,XXL,XXXL',
            'roster.*.isLongSleeve' => 'sometimes|boolean',
        ]);

        // Re-calculate surcharges
        $rosterData = [];
        $totalSurcharge = 0;

        foreach ($request->roster as $player) {
            $isLongSleeve = isset($player['isLongSleeve']) && ($player['isLongSleeve'] == 1 || $player['isLongSleeve'] === true);
            $size = $player['size'];
            
            $playerSurcharge = 0;
            if ($isLongSleeve) $playerSurcharge += 20000;
            if ($size === 'XXL') $playerSurcharge += 5000;
            if ($size === 'XXXL') $playerSurcharge += 10000;

            $rosterData[] = [
                'name' => $player['name'] ?? '',
                'number' => $player['number'] ?? '',
                'size' => $size,
                'isLongSleeve' => $isLongSleeve,
                'surcharge' => $playerSurcharge
            ];
            $totalSurcharge += $playerSurcharge;
        }

        // Add global upgrades surcharge (keep existing)
        $globalUpgradesSurcharge = $item->upgrades->sum('price') * $item->quantity;
        $totalItemSurcharge = $totalSurcharge + $globalUpgradesSurcharge;

        $baseProductPrice = $item->package->base_price + ($item->material?->additional_price ?? 0);
        $newSubtotal = ($baseProductPrice * $item->quantity) + $totalItemSurcharge;

        $item->update([
            'roster' => $rosterData,
            'size_surcharge' => $totalItemSurcharge,
            'subtotal' => $newSubtotal
        ]);

        // Update Order total
        $grandTotal = $order->orderItems()->sum('subtotal');
        $order->update(['total_amount' => $grandTotal]);

        return redirect()->back()->with('success', 'Daftar pemain berhasil diperbarui.');
    }

    /**
     * Update shipping address details for an order.
     */
    public function updateAddress(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Alamat tidak dapat diubah setelah pembayaran dilakukan.');
        }

        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
        ]);

        $order->update($request->only(['recipient_name', 'recipient_phone', 'shipping_address']));

        return redirect()->back()->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    /**
     * Show the full-page edit form for an unpaid order.
     */
    public function edit(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'unpaid') {
            return redirect()->route('customer.orders.show', $order->order_number)->with('error', 'Pesanan tidak dapat diedit setelah pembayaran.');
        }

        $order->load(['orderItems.package', 'orderItems.material', 'orderItems.design', 'orderItems.upgrades']);
        $upgrades = \App\Models\Upgrade::all()->groupBy('category');
        
        // Fetch user designs for the Web Design option
        $userDesigns = \App\Models\Design::where('user_id', Auth::id())
            ->whereNotNull('preview_path')
            ->latest()
            ->get();

        return view('customer.orders.edit', compact('order', 'upgrades', 'userDesigns'));
    }

    /**
     * Handle the full-page update of an order (Roster, Designs, etc.)
     */
    public function updateDetailed(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Status pesanan tidak memungkinkan untuk diubah.');
        }

        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'roster' => 'required|array',
        ]);

        // 1. Update recipient info
        $order->update($request->only(['recipient_name', 'recipient_phone', 'shipping_address']));

        $grandTotal = 0;

        // 2. Update each OrderItem
        foreach ($order->orderItems as $item) {
            $inputs = $request->roster[$item->id] ?? [];
            
            // Re-format roster
            $rosterData = [];
            $itemSurcharge = 0;

            foreach ($inputs as $idx => $player) {
                $isLongSleeve = isset($player['long_sleeve']) && $player['long_sleeve'] == 1;
                $size = $player['size'] ?? 'L';
                
                $playerSurcharge = 0;
                if ($isLongSleeve) $playerSurcharge += 20000;
                if ($size === 'XXL') $playerSurcharge += 5000;
                if ($size === 'XXXL') $playerSurcharge += 10000;

                $rosterData[] = [
                    'name' => $player['name'] ?? '',
                    'number' => $player['number'] ?? '',
                    'size' => $size,
                    'isLongSleeve' => $isLongSleeve,
                    'surcharge' => $playerSurcharge
                ];
                $itemSurcharge += $playerSurcharge;
            }

            // Sync Upgrades
            $itemUpgrades = $request->upgrades[$item->id] ?? [];
            if (is_array($itemUpgrades)) {
                $item->upgrades()->sync($itemUpgrades);
            }

            // Calculate Global Upgrades Surcharge
            $selectedUpgrades = $item->upgrades;
            $globalSurcharge = ($selectedUpgrades->sum('price') * $item->quantity);

            $totalSurcharge = $itemSurcharge + $globalSurcharge;
            $baseProductPrice = $item->package->base_price + ($item->material?->additional_price ?? 0);
            $subtotal = ($baseProductPrice * $item->quantity) + $totalSurcharge;
            $grandTotal += $subtotal;

            $item->update([
                'roster' => $rosterData,
                'size_surcharge' => $totalSurcharge,
                'subtotal' => $subtotal
            ]);

            // Handle Design update - Customizer Selection
            $itemDesignInput = $request->input("designs.{$item->id}");
            if (isset($itemDesignInput['design_id'])) {
                $item->update(['design_id' => $itemDesignInput['design_id']]);
            }

            // Handle Design update - Multiple File Uploads
            if ($request->hasFile("designs.{$item->id}.files")) {
                $filePaths = [];
                foreach ($request->file("designs.{$item->id}.files") as $file) {
                    $filePaths[] = $file->store('orders/designs', 'public');
                }
                
                $design = \App\Models\Design::create([
                    'user_id' => Auth::id(),
                    'design_json' => ['type' => 'uploaded', 'files' => $filePaths]
                ]);
                $item->update(['design_id' => $design->id]);
            }
        }

        $order->update(['total_amount' => $grandTotal]);

        return redirect()->route('customer.orders.show', $order->order_number)->with('success', 'Pesanan berhasil diperbarui.');
    }
}
