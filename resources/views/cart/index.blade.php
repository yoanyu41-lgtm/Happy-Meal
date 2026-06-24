@extends('layouts.app')

@section('title', __('Your Shopping Cart') . ' - Happy Meal')

@section('content')
    <h1 class="cart-title" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem;">
        <span class="material-icons-round" style="color: var(--primary-color); font-size: 2.25rem;">shopping_cart</span>
        <span>{{ __('Your Shopping Cart') }}</span>
    </h1>

    @if(count($cartItems) > 0)
        <div class="cart-layout">
            <!-- Cart Items List -->
            <div class="cart-card">
                <div class="cart-items-list">
                    @foreach($cartItems as $item)
                        <div class="cart-item">
                            <!-- Product Image -->
                            <div class="cart-item-img">
                                @if($item->product->image)
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}">
                                @else
                                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#1e293b;">
                                        <span class="material-icons-round" style="color: var(--text-secondary);">image</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Name & Price -->
                            <div class="cart-item-details">
                                <a href="{{ route('products.show', $item->product->id) }}" class="cart-item-name">{{ $item->product->name }}</a>
                                
                                @if(!empty($item->options))
                                    <div class="cart-item-options" style="font-size: 0.8rem; color: var(--primary-color); margin: 0.25rem 0; display: flex; flex-direction: column; gap: 0.15rem;">
                                        @if(isset($item->options['spice']))
                                            <span>{{ __('Spice Level') }}: <strong>{{ __($item->options['spice']) }}</strong></span>
                                        @endif
                                        @if(isset($item->options['sweetness']))
                                            <span>{{ __('Sweetness Level') }}: <strong>{{ $item->options['sweetness'] }}</strong></span>
                                        @endif
                                        @if(isset($item->options['ice']))
                                            <span>{{ __('Ice Level') }}: <strong>{{ $item->options['ice'] }}</strong></span>
                                        @endif
                                        @if(isset($item->options['addons']) && count($item->options['addons']) > 0)
                                            <span>{{ __('Add-ons') }}: 
                                                <strong>
                                                    @foreach($item->options['addons'] as $addon)
                                                        {{ $item->product->getAddonLabel($addon) }}{{ !$loop->last ? ', ' : '' }}
                                                    @endforeach
                                                </strong>
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="cart-item-price">${{ number_format($item->item_price, 2) }} / {{ __('dish') }}</div>
                            </div>

                            <!-- Quantity Form & Delete -->
                            <div class="cart-item-actions">
                                <form action="{{ route('cart.update', $item->key) }}" method="POST" style="display: flex; align-items: center; gap: 0.5rem;">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="form-control" style="width: 70px; padding: 0.4rem 0.5rem; text-align: center;" onchange="this.form.submit()">
                                    <span style="font-size: 0.9rem; color: var(--text-secondary);">{{ __('dishes') }}</span>
                                </form>

                                <form action="{{ route('cart.remove', $item->key) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.5rem; color: var(--danger-color); border-color: rgba(239, 68, 68, 0.2);" aria-label="{{ __('Delete') }}">
                                        <span class="material-icons-round" style="font-size: 1.25rem;">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Cart Summary Box -->
            <div class="cart-summary">
                <h2 class="summary-title">{{ __('Order Summary') }}</h2>
                
                <div class="summary-row">
                    <span style="color: var(--text-secondary);">{{ __('Total Items') }}</span>
                    <span>{{ count($cartItems) }} {{ __('items') }}</span>
                </div>
                
                <div class="summary-row">
                    <span style="color: var(--text-secondary);">{{ __('Shipping') }}</span>
                    <span style="color: var(--success-color);">{{ __('Free Delivery') }}</span>
                </div>

                <div class="summary-row summary-total">
                    <span>{{ __('Total') }}</span>
                    <span style="color: var(--primary-color); font-size: 1.5rem;">${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">
                    <span class="material-icons-round">payment</span>
                    <span>{{ __('Proceed to Checkout') }}</span>
                </a>
                
                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="width: 100%; margin-top: 0.75rem;">
                    <span class="material-icons-round">restaurant</span>
                    <span>{{ __('Order More Food') }}</span>
                </a>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 6rem 2rem; background: var(--bg-card); border-radius: 16px; border: 1px solid var(--border-color); box-shadow: var(--card-shadow);">
            <span class="material-icons-round" style="font-size: 6rem; color: var(--text-secondary); margin-bottom: 1.5rem;">shopping_cart</span>
            <h2 style="font-size: 2rem; margin-bottom: 0.75rem;">{{ __('Your cart is empty') }}</h2>
            <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 2rem auto;">
                {{ __('You have not ordered any dishes yet. Please select delicious dishes from our shop.') }}
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <span class="material-icons-round">restaurant_menu</span>
                <span>{{ __('Go to Menu') }}</span>
            </a>
        </div>
    @endif
@endsection
