<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'image', 'stock', 'category', 'prep_time_minutes',
        'has_spice_level', 'has_sweetness_level', 'has_ice_level', 'addons_config'
    ];

    protected $casts = [
        'has_spice_level' => 'boolean',
        'has_sweetness_level' => 'boolean',
        'has_ice_level' => 'boolean',
        'addons_config' => 'array',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isAddonEnabled(string $addonKey): bool
    {
        if (str_starts_with($addonKey, 'custom_')) {
            $index = (int) str_replace('custom_', '', $addonKey);
            return isset($this->addons_config['custom'][$index]) && !empty($this->addons_config['custom'][$index]['name']);
        }

        if (empty($this->addons_config)) {
            // Pre-default behavior: Jelly & Coconut enabled for drinks, Egg & Meat for food
            if ($this->category === 'drinks') {
                return in_array($addonKey, ['jelly', 'coconut']);
            }
            return in_array($addonKey, ['egg', 'meat']);
        }
        return !empty($this->addons_config[$addonKey]['enabled']);
    }

    public function getAddonPrice(string $addonKey, float $defaultPrice = 0.50): float
    {
        if (str_starts_with($addonKey, 'custom_')) {
            $index = (int) str_replace('custom_', '', $addonKey);
            return (float) ($this->addons_config['custom'][$index]['price'] ?? 0.00);
        }

        if (empty($this->addons_config) || !isset($this->addons_config[$addonKey]['price'])) {
            return $addonKey === 'meat' ? 1.50 : 0.50;
        }
        return (float) $this->addons_config[$addonKey]['price'];
    }

    public function getAddonLabel(string $addonKey): string
    {
        if (str_starts_with($addonKey, 'custom_')) {
            $index = (int) str_replace('custom_', '', $addonKey);
            return $this->addons_config['custom'][$index]['name'] ?? $addonKey;
        }

        if (isset($this->addons_config[$addonKey]['name']) && !empty($this->addons_config[$addonKey]['name'])) {
            return $this->addons_config[$addonKey]['name'];
        }

        $labels = [
            'egg' => __('Extra Egg'),
            'meat' => __('Extra Meat'),
            'jelly' => __('Extra Jelly'),
            'coconut' => __('Extra Coconut Jelly'),
        ];

        return $labels[$addonKey] ?? __($addonKey);
    }
}
