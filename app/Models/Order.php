<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'payment_proof',
        'payment_verified',
        'estimated_delivery_minutes',
    ];

    protected $casts = [
        'payment_verified' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function autoUpdateStatusBasedOnTime()
    {
        if (!$this->estimated_delivery_minutes) {
            return;
        }

        // If it is already delivered or cancelled, don't auto-update
        if (in_array($this->status, ['delivered', 'cancelled'])) {
            return;
        }

        $totalSeconds = $this->estimated_delivery_minutes * 60;
        if ($totalSeconds <= 0) {
            return;
        }

        $elapsedSeconds = max(0, now()->timestamp - $this->created_at->timestamp);
        $ratio = $elapsedSeconds / $totalSeconds;

        $newStatus = $this->status;

        if ($ratio >= 1.0) {
            $newStatus = 'delivered';
        } elseif ($ratio >= 0.75) {
            $newStatus = 'out_for_delivery';
        } elseif ($ratio >= 0.30) {
            $newStatus = 'preparing';
        } elseif ($ratio >= 0.10) {
            $newStatus = 'confirmed';
        } else {
            $newStatus = 'pending';
        }

        // Only save if the status changed and we are moving forward
        $statusOrder = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
        $oldIndex = array_search($this->status, $statusOrder);
        $newIndex = array_search($newStatus, $statusOrder);

        if ($newIndex !== false && $oldIndex !== false && $newIndex > $oldIndex) {
            $this->status = $newStatus;
            $this->save();
        }
    }
}
