@extends('layouts.app')

@section('title', $product->name . ' - Happy Meal')

@section('content')
    <!-- Back Button -->
    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        <span class="material-icons-round" style="font-size: 1.15rem;">arrow_back</span>
        <span>{{ __('Go Back') }}</span>
    </a>

    <!-- Product Detail Layout -->
    <div class="product-detail-layout">
        <!-- Product Image -->
        <div class="detail-image-card">
            @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            @else
                <div style="width: 100%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; background: #1e293b;">
                    <span class="material-icons-round" style="font-size: 6rem; color: var(--text-secondary);">image</span>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="detail-info">
            <h1 class="detail-title">{{ $product->name }}</h1>
            <div class="detail-price">${{ number_format($product->price, 2) }}</div>
            
            <p class="detail-desc">{{ $product->description }}</p>

            <div class="detail-meta">
                <div class="meta-row">
                    <span style="color: var(--text-secondary);">{{ __('Availability') }}</span>
                    @if($product->stock > 0)
                        <span class="stock-badge stock-in">{{ __('Available') }} ({{ $product->stock }} {{ $product->stock > 1 ? __('dishes') : __('dish') }})</span>
                    @else
                        <span class="stock-badge stock-out">{{ __('Out of Stock') }}</span>
                    @endif
                </div>
                <div class="meta-row" style="margin-top: 0.5rem;">
                    <span style="color: var(--text-secondary);">{{ __('Shipping') }}</span>
                    <span style="font-weight: 600; color: var(--success-color);">{{ __('Free Delivery') }}</span>
                </div>
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form" style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @php
                        $hasAnyCustomization = $product->has_spice_level || 
                                               $product->has_sweetness_level || 
                                               $product->has_ice_level || 
                                               $product->isAddonEnabled('egg') || 
                                               $product->isAddonEnabled('meat') || 
                                               $product->isAddonEnabled('jelly') || 
                                               $product->isAddonEnabled('coconut') ||
                                               (!empty($product->addons_config['custom']) && count($product->addons_config['custom']) > 0);
                    @endphp

                    <!-- Customizations -->
                    @if($hasAnyCustomization)
                    <div class="customization-section" style="background: var(--bg-input); border: 1px solid var(--border-color); padding: 1.25rem; border-radius: 12px; display: flex; flex-direction: column; gap: 1.25rem;">
                        <h3 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 0.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                            <span class="material-icons-round" style="color: var(--primary-color); font-size: 1.25rem;">tune</span>
                            <span>{{ __('Customization Options') }}</span>
                        </h3>

                        @if($product->category === 'drinks')
                            @if($product->has_sweetness_level)
                            <!-- Sweetness Level -->
                            <div class="option-group">
                                <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-primary);">
                                    {{ __('Sweetness Level') }}
                                </label>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="sweetness_level" value="100%" checked style="display:none;">
                                        <span class="option-label">100% ({{ __('Normal') }})</span>
                                    </label>
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="sweetness_level" value="50%" style="display:none;">
                                        <span class="option-label">50%</span>
                                    </label>
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="sweetness_level" value="0%" style="display:none;">
                                        <span class="option-label">0% ({{ __('No Sugar') }})</span>
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if($product->has_ice_level)
                            <!-- Ice Level -->
                            <div class="option-group" style="margin-top: 0.75rem;">
                                <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-primary);">
                                    {{ __('Ice Level') }}
                                </label>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="ice_level" value="100%" checked style="display:none;">
                                        <span class="option-label">100% ({{ __('Normal') }})</span>
                                    </label>
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="ice_level" value="50%" style="display:none;">
                                        <span class="option-label">50%</span>
                                    </label>
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="ice_level" value="0%" style="display:none;">
                                        <span class="option-label">0% ({{ __('No Ice') }})</span>
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if($product->isAddonEnabled('jelly') || $product->isAddonEnabled('coconut') || (!empty($product->addons_config['custom']) && count($product->addons_config['custom']) > 0))
                            <!-- Drink Toppings -->
                            <div class="option-group" style="margin-top: 0.75rem;">
                                <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-primary);">
                                    {{ __('Add-on Toppings') }}
                                </label>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    @if($product->isAddonEnabled('jelly'))
                                    <label class="custom-checkbox-row" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" name="addons[]" value="jelly" class="addon-checkbox" data-price="{{ $product->getAddonPrice('jelly') }}" style="accent-color: var(--primary-color);">
                                            <span>{{ $product->getAddonLabel('jelly') }}</span>
                                        </div>
                                        <span style="font-weight: 600; color: var(--primary-color);">+${{ number_format($product->getAddonPrice('jelly'), 2) }}</span>
                                    </label>
                                    @endif
                                    @if($product->isAddonEnabled('coconut'))
                                    <label class="custom-checkbox-row" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" name="addons[]" value="coconut" class="addon-checkbox" data-price="{{ $product->getAddonPrice('coconut') }}" style="accent-color: var(--primary-color);">
                                            <span>{{ $product->getAddonLabel('coconut') }}</span>
                                        </div>
                                        <span style="font-weight: 600; color: var(--primary-color);">+${{ number_format($product->getAddonPrice('coconut'), 2) }}</span>
                                    </label>
                                    @endif
                                    @if(!empty($product->addons_config['custom']))
                                        @foreach($product->addons_config['custom'] as $index => $addon)
                                            <label class="custom-checkbox-row" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer;">
                                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                    <input type="checkbox" name="addons[]" value="custom_{{ $index }}" class="addon-checkbox" data-price="{{ $addon['price'] }}" style="accent-color: var(--primary-color);">
                                                    <span>{{ $addon['name'] }}</span>
                                                </div>
                                                <span style="font-weight: 600; color: var(--primary-color);">+${{ number_format($addon['price'], 2) }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
                        @else
                            @if($product->has_spice_level)
                            <!-- Spice Level -->
                            <div class="option-group">
                                <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-primary);">
                                    {{ __('Spice Level') }}
                                </label>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="spice_level" value="not_spicy" style="display:none;">
                                        <span class="option-label">{{ __('Not Spicy') }}</span>
                                    </label>
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="spice_level" value="medium" checked style="display:none;">
                                        <span class="option-label">{{ __('Medium Spicy') }}</span>
                                    </label>
                                    <label class="custom-radio-btn" style="flex: 1; text-align: center;">
                                        <input type="radio" name="spice_level" value="extra_spicy" style="display:none;">
                                        <span class="option-label">{{ __('Extra Spicy') }}</span>
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if($product->isAddonEnabled('egg') || $product->isAddonEnabled('meat') || (!empty($product->addons_config['custom']) && count($product->addons_config['custom']) > 0))
                            <!-- Food Add-ons -->
                            <div class="option-group" style="margin-top: 0.75rem;">
                                <label style="font-weight: 600; display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-primary);">
                                    {{ __('Add-on options') }}
                                </label>
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    @if($product->isAddonEnabled('egg'))
                                    <label class="custom-checkbox-row" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" name="addons[]" value="egg" class="addon-checkbox" data-price="{{ $product->getAddonPrice('egg') }}" style="accent-color: var(--primary-color);">
                                            <span>{{ $product->getAddonLabel('egg') }}</span>
                                        </div>
                                        <span style="font-weight: 600; color: var(--primary-color);">+${{ number_format($product->getAddonPrice('egg'), 2) }}</span>
                                    </label>
                                    @endif
                                    @if($product->isAddonEnabled('meat'))
                                    <label class="custom-checkbox-row" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" name="addons[]" value="meat" class="addon-checkbox" data-price="{{ $product->getAddonPrice('meat') }}" style="accent-color: var(--primary-color);">
                                            <span>{{ $product->getAddonLabel('meat') }}</span>
                                        </div>
                                        <span style="font-weight: 600; color: var(--primary-color);">+${{ number_format($product->getAddonPrice('meat'), 2) }}</span>
                                    </label>
                                    @endif
                                    @if(!empty($product->addons_config['custom']))
                                        @foreach($product->addons_config['custom'] as $index => $addon)
                                            <label class="custom-checkbox-row" style="display: flex; align-items: center; justify-content: space-between; padding: 0.6rem 0.75rem; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer;">
                                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                    <input type="checkbox" name="addons[]" value="custom_{{ $index }}" class="addon-checkbox" data-price="{{ $addon['price'] }}" style="accent-color: var(--primary-color);">
                                                    <span>{{ $addon['name'] }}</span>
                                                </div>
                                                <span style="font-weight: 600; color: var(--primary-color);">+${{ number_format($addon['price'], 2) }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                    @endif

                    <!-- Quantity & Action Row -->
                    <div style="display: flex; gap: 1rem; align-items: center; width: 100%;">
                        <select name="quantity" class="qty-select" id="qty-select" aria-label="{{ __('Quantity') }}" style="padding: 0.8rem 1rem; border-radius: 8px; background: var(--bg-card); color: var(--text-primary); border: 1px solid var(--border-color); font-weight: 600; min-width: 120px;">
                            @for($i = 1; $i <= min($product->stock, 10); $i++)
                                <option value="{{ $i }}">{{ $i }} {{ $i > 1 ? __('dishes') : __('dish') }}</option>
                            @endfor
                        </select>

                        <button type="submit" class="btn btn-primary" style="flex-grow: 1; padding: 0.8rem 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 8px; font-weight: 700;">
                            <span class="material-icons-round">add_shopping_cart</span>
                            <span>{{ __('Add to Order Cart') }}</span>
                            <span id="addon-total-badge" style="font-size: 0.85rem; padding: 0.1rem 0.4rem; border-radius: 10px; background: rgba(255,255,255,0.25); display: none;"></span>
                        </button>
                    </div>
                </form>
            @else
                <button class="btn btn-secondary" disabled style="width: 100%; cursor: not-allowed; opacity: 0.6;">
                    <span class="material-icons-round">sentiment_dissatisfied</span>
                    <span>{{ __('Out of Stock') }}</span>
                </button>
            @endif
        </div>
    </div>

    <style>
        /* Custom styles for radios */
        .custom-radio-btn {
            position: relative;
            cursor: pointer;
        }
        .custom-radio-btn input[type="radio"]:checked + .option-label {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 8px var(--primary-glow);
        }
        .custom-radio-btn .option-label {
            display: block;
            padding: 0.6rem 0.75rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all var(--transition-speed);
            color: var(--text-primary);
        }
        .custom-radio-btn .option-label:hover {
            border-color: var(--primary-color);
            background: rgba(249, 115, 22, 0.05);
        }
        .custom-checkbox-row:hover {
            border-color: var(--primary-color) !important;
            background: rgba(249, 115, 22, 0.05) !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const basePrice = {{ $product->price }};
            const checkboxes = document.querySelectorAll('.addon-checkbox');
            const qtySelect = document.getElementById('qty-select');
            const badge = document.getElementById('addon-total-badge');
            
            function updatePrice() {
                let addonTotal = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        addonTotal += parseFloat(cb.dataset.price);
                    }
                });
                
                const quantity = parseInt(qtySelect.value) || 1;
                const singlePrice = basePrice + addonTotal;
                const total = singlePrice * quantity;
                
                if (addonTotal > 0) {
                    badge.textContent = `+$${(addonTotal * quantity).toFixed(2)}`;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
            
            checkboxes.forEach(cb => cb.addEventListener('change', updatePrice));
            if (qtySelect) qtySelect.addEventListener('change', updatePrice);
            
            updatePrice();
        });
    </script>
@endsection
