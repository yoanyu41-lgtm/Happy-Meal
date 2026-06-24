<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Fetch products in cart
        $cartItems = [];
        $total = 0;
        
        if (!empty($cart)) {
            $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
            
            foreach ($cart as $key => $details) {
                $productId = $details['product_id'];
                if (isset($products[$productId])) {
                    $product = $products[$productId];
                    $quantity = $details['quantity'];
                    $addonPrice = $details['addon_price'] ?? 0;
                    $itemPrice = $product->price + $addonPrice;
                    $subtotal = $itemPrice * $quantity;
                    $total += $subtotal;
                    
                    $cartItems[] = (object)[
                        'key' => $key,
                        'product' => $product,
                        'quantity' => $quantity,
                        'options' => $details['options'] ?? [],
                        'addon_price' => $addonPrice,
                        'item_price' => $itemPrice,
                        'subtotal' => $subtotal
                    ];
                }
            }
        }
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int)$request->input('quantity', 1);
        
        $product = Product::findOrFail($productId);
        
        // Gather options based on category
        $options = [];
        if ($product->category === 'drinks') {
            if ($request->has('sweetness_level')) {
                $options['sweetness'] = $request->input('sweetness_level');
            }
            if ($request->has('ice_level')) {
                $options['ice'] = $request->input('ice_level');
            }
            if ($request->has('addons')) {
                $options['addons'] = $request->input('addons'); // e.g. ['jelly', 'coconut']
            }
        } else {
            if ($request->has('spice_level')) {
                $options['spice'] = $request->input('spice_level');
            }
            if ($request->has('addons')) {
                $options['addons'] = $request->input('addons'); // e.g. ['egg', 'meat']
            }
        }
        
        // Calculate additional price based on addons
        $addonPrice = 0;
        if (isset($options['addons']) && is_array($options['addons'])) {
            foreach ($options['addons'] as $addon) {
                $addonPrice += $product->getAddonPrice($addon);
            }
        }
        
        // Generate unique key based on product ID and options hash
        $optionsHash = !empty($options) ? md5(json_encode($options)) : 'default';
        $cartKey = $productId . ':' . $optionsHash;
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'options' => $options,
                'addon_price' => $addonPrice
            ];
        }
        
        // Ensure quantity doesn't exceed stock
        if ($cart[$cartKey]['quantity'] > $product->stock) {
            $cart[$cartKey]['quantity'] = $product->stock;
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', __('Product added to cart successfully!'));
    }

    public function update(Request $request, $key)
    {
        $quantity = (int)$request->input('quantity', 1);
        $cart = session()->get('cart', []);
        
        if (isset($cart[$key])) {
            $productId = $cart[$key]['product_id'];
            $product = Product::findOrFail($productId);
            
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = min($quantity, $product->stock);
            }
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', __('Cart updated!'));
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', __('Product removed from cart!'));
    }
}
