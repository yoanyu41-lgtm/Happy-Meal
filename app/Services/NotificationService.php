<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusMail;
use App\Models\Order;

class NotificationService
{
    /**
     * Send a notification message to the Admin Telegram channel/chat.
     * Falls back to standard Laravel Log if keys are not set.
     */
    public static function sendAdminTelegram(string $message): void
    {
        $botToken = config('services.telegram.bot_token');
        $chatId = config('services.telegram.admin_chat_id');

        if (empty($botToken) || empty($chatId)) {
            Log::info("Telegram notification fallback (missing bot token or chat ID):\n{$message}");
            return;
        }

        try {
            $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
            $response = Http::timeout(5)->post($url, [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if ($response->failed()) {
                Log::error("Failed to send Telegram notification. Status: {$response->status()}, Response: {$response->body()}");
            }
        } catch (\Exception $e) {
            Log::error("Exception occurred while sending Telegram notification: " . $e->getMessage());
        }
    }

    /**
     * Send order status email to customer.
     */
    public static function sendCustomerStatusEmail(Order $order): void
    {
        if (empty($order->customer_email)) {
            Log::warning("Cannot send order status email: customer email is empty for Order ID: {$order->id}");
            return;
        }

        try {
            Mail::to($order->customer_email)->send(new OrderStatusMail($order));
        } catch (\Exception $e) {
            Log::error("Failed to send email to customer for Order ID: {$order->id}. Error: " . $e->getMessage());
        }
    }
}
