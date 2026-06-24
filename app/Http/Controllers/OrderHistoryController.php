<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($orders as $order) {
            $order->autoUpdateStatusBasedOnTime();
        }

        return view('customer.orders', compact('orders'));
    }
}
