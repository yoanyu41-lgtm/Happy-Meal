<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', __('Your cart is empty!'));
        }
        
        $total = 0;
        $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        foreach ($cart as $key => $details) {
            $productId = $details['product_id'];
            if (isset($products[$productId])) {
                $product = $products[$productId];
                $addonPrice = $details['addon_price'] ?? 0;
                $total += ($product->price + $addonPrice) * $details['quantity'];
            }
        }
        
        return view('cart.checkout', compact('total', 'cart', 'products'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', __('Your cart is empty!'));
        }
        
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'payment_proof'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        
        try {
            DB::beginTransaction();
            
            $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            $total = 0;
            
            // Calculate total and verify stock
            foreach ($cart as $key => $details) {
                $productId = $details['product_id'];
                if (!isset($products[$productId])) {
                    return redirect()->route('cart.index')->with('error', __('Some items in your cart are no longer available.'));
                }
                
                $product = $products[$productId];
                if ($product->stock < $details['quantity']) {
                    return redirect()->route('cart.index')->with('error', __('Not enough stock for ') . $product->name);
                }
                
                $addonPrice = $details['addon_price'] ?? 0;
                $total += ($product->price + $addonPrice) * $details['quantity'];
            }
            
            // Handle payment proof upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')
                    ->store('payment_proofs', 'public');
            }

            // Calculate dynamic estimated delivery minutes
            $maxPrepTime = 15;
            if (!empty($cart)) {
                $maxPrepTime = collect($cart)->map(function ($item) use ($products) {
                    $prod = $products[$item['product_id']] ?? null;
                    return $prod ? ($prod->prep_time_minutes ?? 15) : 15;
                })->max();
            }
            $estimatedDeliveryMinutes = $maxPrepTime + 15;

            // Create Order
            $order = Order::create([
                'user_id'          => auth()->id(),
                'customer_name'    => $request->input('customer_name'),
                'customer_email'   => $request->input('customer_email'),
                'customer_phone'   => $request->input('customer_phone'),
                'customer_address' => $request->input('customer_address'),
                'total_amount'     => $total,
                'status'           => 'pending',
                'payment_proof'    => $paymentProofPath,
                'payment_verified' => false,
                'estimated_delivery_minutes' => $estimatedDeliveryMinutes,
            ]);
            
            // Create Order Items and update product stock
            foreach ($cart as $key => $details) {
                $productId = $details['product_id'];
                $product = $products[$productId];
                $addonPrice = $details['addon_price'] ?? 0;
                $finalUnitPrice = $product->price + $addonPrice;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'price' => $finalUnitPrice,
                    'options' => $details['options'] ?? []
                ]);
                
                // Deduct stock
                $product->stock -= $details['quantity'];
                $product->save();
            }
            
            DB::commit();
            
            // Clear cart
            session()->forget('cart');
            
            // Trigger Admin Telegram Notification
            try {
                $itemsSummary = '';
                foreach ($order->items as $item) {
                    $productName = $item->product ? $item->product->name : __('Deleted Product');
                    $itemsSummary .= "• {$productName} (x{$item->quantity})\n";
                }
                
                $telegramMessage = "🔔 <b>New Order Received / ការបញ្ជាទិញថ្មី</b>\n\n"
                    . "<b>Order ID:</b> #{$order->id}\n"
                    . "<b>Customer:</b> {$order->customer_name}\n"
                    . "<b>Phone:</b> {$order->customer_phone}\n"
                    . "<b>Address:</b> {$order->customer_address}\n"
                    . "<b>Total Amount:</b> \${$order->total_amount}\n"
                    . "<b>Status:</b> Pending Confirmation\n\n"
                    . "<b>Items Ordered:</b>\n{$itemsSummary}\n"
                    . "<a href=\"" . route('admin.dashboard') . "\">👉 View on Admin Dashboard</a>";
                    
                \App\Services\NotificationService::sendAdminTelegram($telegramMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to notify admin on new order: " . $e->getMessage());
            }
            
            return redirect()->route('checkout.success', $order->id)->with('success', __('Order placed successfully!'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong: ') . $e->getMessage())->withInput();
        }
    }

    public function success($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $order->autoUpdateStatusBasedOnTime();
        return view('cart.success', compact('order'));
    }

    public function uploadProof(Request $request, Order $order)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('payment_proof')) {
            if ($order->payment_proof) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_proof);
            }

            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            
            $order->update([
                'payment_proof' => $path,
                'payment_verified' => false,
            ]);

            // Trigger Admin Telegram Notification for payment proof upload
            try {
                $telegramMessage = "📸 <b>Payment Proof Uploaded / រូបភាពបង់ប្រាក់ត្រូវបានផ្ញើ</b>\n\n"
                    . "<b>Order ID:</b> #{$order->id}\n"
                    . "<b>Customer:</b> {$order->customer_name}\n"
                    . "<b>Phone:</b> {$order->customer_phone}\n"
                    . "<b>Total Amount:</b> \${$order->total_amount}\n\n"
                    . "Please review and verify this payment in the dashboard.\n"
                    . "<a href=\"" . route('admin.dashboard') . "\">👉 Go to Admin Dashboard</a>";
                    
                \App\Services\NotificationService::sendAdminTelegram($telegramMessage);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to notify admin on payment proof upload: " . $e->getMessage());
            }

            return redirect()->back()->with('success', __('Payment proof uploaded successfully!'));
        }

        return redirect()->back()->with('error', __('Failed to upload payment proof.'));
    }

    public function getStatus(Order $order)
    {
        $order->autoUpdateStatusBasedOnTime();
        $remainingSeconds = 0;
        if ($order->estimated_delivery_minutes) {
            $expiryTimestamp = $order->created_at->timestamp + ($order->estimated_delivery_minutes * 60);
            $remainingSeconds = max(0, $expiryTimestamp - now()->timestamp);
        }

        return response()->json([
            'status' => $order->status,
            'payment_verified' => $order->payment_verified,
            'payment_proof' => $order->payment_proof ? asset('storage/' . $order->payment_proof) : null,
            'estimated_delivery_minutes' => $order->estimated_delivery_minutes,
            'remaining_seconds' => $remainingSeconds,
            'created_at' => $order->created_at->toIso8601String(),
        ]);
    }
}
