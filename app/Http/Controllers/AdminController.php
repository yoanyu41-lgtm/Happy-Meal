<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $orders = Order::with('items.product')->orderBy('created_at', 'desc')->get();

        foreach ($orders as $order) {
            $order->autoUpdateStatusBasedOnTime();
        }

        // Calculate statistics
        $totalRevenue = Order::where('payment_verified', true)->sum('total_amount');
        $totalOrdersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalProductsCount = Product::count();

        return view('admin.dashboard', compact(
            'products',
            'orders',
            'totalRevenue',
            'totalOrdersCount',
            'pendingOrdersCount',
            'totalProductsCount'
        ));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|in:breakfast,alacarte,night,drinks',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'prep_time_minutes' => 'required|integer|min:0',
            'has_spice_level' => 'nullable|boolean',
            'has_sweetness_level' => 'nullable|boolean',
            'has_ice_level' => 'nullable|boolean',
            'addon_egg_enabled' => 'nullable|boolean',
            'addon_egg_name' => 'nullable|string|max:255',
            'addon_egg_price' => 'nullable|numeric|min:0',
            'addon_meat_enabled' => 'nullable|boolean',
            'addon_meat_name' => 'nullable|string|max:255',
            'addon_meat_price' => 'nullable|numeric|min:0',
            'addon_jelly_enabled' => 'nullable|boolean',
            'addon_jelly_name' => 'nullable|string|max:255',
            'addon_jelly_price' => 'nullable|numeric|min:0',
            'addon_coconut_enabled' => 'nullable|boolean',
            'addon_coconut_name' => 'nullable|string|max:255',
            'addon_coconut_price' => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock', 'category', 'prep_time_minutes']);
        
        $data['has_spice_level'] = $request->has('has_spice_level');
        $data['has_sweetness_level'] = $request->has('has_sweetness_level');
        $data['has_ice_level'] = $request->has('has_ice_level');
        
        $customAddons = [];
        if ($request->has('custom_addons')) {
            foreach ($request->input('custom_addons') as $addon) {
                if (isset($addon['name']) && trim($addon['name']) !== '') {
                    $customAddons[] = [
                        'name' => trim($addon['name']),
                        'price' => (float)($addon['price'] ?? 0.00),
                    ];
                }
            }
        }

        $data['addons_config'] = [
            'egg' => [
                'enabled' => $request->has('addon_egg_enabled'),
                'name' => $request->input('addon_egg_name'),
                'price' => (float)$request->input('addon_egg_price', 0.50),
            ],
            'meat' => [
                'enabled' => $request->has('addon_meat_enabled'),
                'name' => $request->input('addon_meat_name'),
                'price' => (float)$request->input('addon_meat_price', 1.50),
            ],
            'jelly' => [
                'enabled' => $request->has('addon_jelly_enabled'),
                'name' => $request->input('addon_jelly_name'),
                'price' => (float)$request->input('addon_jelly_price', 0.50),
            ],
            'coconut' => [
                'enabled' => $request->has('addon_coconut_enabled'),
                'name' => $request->input('addon_coconut_name'),
                'price' => (float)$request->input('addon_coconut_price', 0.50),
            ],
            'custom' => $customAddons,
        ];

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $data['image'] = 'images/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('admin.dashboard')->with('success', __('Product created successfully!'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|in:breakfast,alacarte,night,drinks',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'prep_time_minutes' => 'required|integer|min:0',
            'has_spice_level' => 'nullable|boolean',
            'has_sweetness_level' => 'nullable|boolean',
            'has_ice_level' => 'nullable|boolean',
            'addon_egg_enabled' => 'nullable|boolean',
            'addon_egg_name' => 'nullable|string|max:255',
            'addon_egg_price' => 'nullable|numeric|min:0',
            'addon_meat_enabled' => 'nullable|boolean',
            'addon_meat_name' => 'nullable|string|max:255',
            'addon_meat_price' => 'nullable|numeric|min:0',
            'addon_jelly_enabled' => 'nullable|boolean',
            'addon_jelly_name' => 'nullable|string|max:255',
            'addon_jelly_price' => 'nullable|numeric|min:0',
            'addon_coconut_enabled' => 'nullable|boolean',
            'addon_coconut_name' => 'nullable|string|max:255',
            'addon_coconut_price' => 'nullable|numeric|min:0',
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock', 'category', 'prep_time_minutes']);
        
        $data['has_spice_level'] = $request->has('has_spice_level');
        $data['has_sweetness_level'] = $request->has('has_sweetness_level');
        $data['has_ice_level'] = $request->has('has_ice_level');
        
        $customAddons = [];
        if ($request->has('custom_addons')) {
            foreach ($request->input('custom_addons') as $addon) {
                if (isset($addon['name']) && trim($addon['name']) !== '') {
                    $customAddons[] = [
                        'name' => trim($addon['name']),
                        'price' => (float)($addon['price'] ?? 0.00),
                    ];
                }
            }
        }

        $data['addons_config'] = [
            'egg' => [
                'enabled' => $request->has('addon_egg_enabled'),
                'name' => $request->input('addon_egg_name'),
                'price' => (float)$request->input('addon_egg_price', 0.50),
            ],
            'meat' => [
                'enabled' => $request->has('addon_meat_enabled'),
                'name' => $request->input('addon_meat_name'),
                'price' => (float)$request->input('addon_meat_price', 1.50),
            ],
            'jelly' => [
                'enabled' => $request->has('addon_jelly_enabled'),
                'name' => $request->input('addon_jelly_name'),
                'price' => (float)$request->input('addon_jelly_price', 0.50),
            ],
            'coconut' => [
                'enabled' => $request->has('addon_coconut_enabled'),
                'name' => $request->input('addon_coconut_name'),
                'price' => (float)$request->input('addon_coconut_price', 0.50),
            ],
            'custom' => $customAddons,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && File::exists(public_path($product->image))) {
                // Keep default seed images intact, delete uploaded ones
                if (!str_contains($product->image, 'zenith_watch') && 
                    !str_contains($product->image, 'aura_headphones') && 
                    !str_contains($product->image, 'neo_runners') && 
                    !str_contains($product->image, 'keycraft_keyboard')) {
                    File::delete(public_path($product->image));
                }
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $data['image'] = 'images/products/' . $imageName;
        }

        $product->update($data);

        return redirect()->route('admin.dashboard')->with('success', __('Product updated successfully!'));
    }

    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path($product->image))) {
            if (!str_contains($product->image, 'zenith_watch') && 
                !str_contains($product->image, 'aura_headphones') && 
                !str_contains($product->image, 'neo_runners') && 
                !str_contains($product->image, 'keycraft_keyboard')) {
                File::delete(public_path($product->image));
            }
        }

        $product->delete();

        return redirect()->route('admin.dashboard')->with('success', __('Product deleted successfully!'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,preparing,out_for_delivery,delivered',
        ]);

        $order->status = $request->input('status');
        $order->save();

        // Notify customer about status change
        \App\Services\NotificationService::sendCustomerStatusEmail($order);

        return response()->json([
            'success' => true,
            'message' => __('Status updated!'),
            'status'  => $order->status,
        ]);
    }

    public function verifyPayment(Order $order)
    {
        $order->payment_verified = true;
        if ($order->status === 'pending') {
            $order->status = 'confirmed';
        }
        $order->save();

        // Notify customer about payment verification and potential status change
        \App\Services\NotificationService::sendCustomerStatusEmail($order);

        return redirect()->back()->with('success', __('Payment verified successfully!'));
    }

    public function updateDeliveryMinutes(Request $request, Order $order)
    {
        $request->validate([
            'delivery_minutes' => 'nullable|integer|min:0|max:1440',
        ]);

        $order->estimated_delivery_minutes = $request->input('delivery_minutes');
        
        // Auto-confirm order status if currently pending and duration is set
        if ($order->status === 'pending' && $request->input('delivery_minutes')) {
            $order->status = 'confirmed';
        }
        
        $order->save();

        // Notify customer about delivery time estimation and potential status change
        \App\Services\NotificationService::sendCustomerStatusEmail($order);

        return response()->json([
            'success' => true,
            'message' => __('Estimated delivery time updated!'),
            'estimated_delivery_minutes' => $order->estimated_delivery_minutes,
            'status' => $order->status,
        ]);
    }

    public function destroyOrder(Order $order)
    {
        if ($order->payment_proof) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->payment_proof);
        }

        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.dashboard')->with('success', __('Order deleted successfully!'));
    }
}
