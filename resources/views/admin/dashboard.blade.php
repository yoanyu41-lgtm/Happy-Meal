@extends('layouts.app')

@section('title', __('Admin Dashboard') . ' - Happy Meal')

@section('content')

    {{-- Toast Notification --}}
    <div id="status-toast" style="
        position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999;
        display: flex; align-items: center; gap: 0.6rem;
        background: var(--bg-card); border: 1px solid var(--border-color);
        border-radius: 12px; padding: 0.85rem 1.25rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.35);
        opacity: 0; transform: translateY(-12px);
        transition: opacity 0.3s ease, transform 0.3s ease;
        pointer-events: none; min-width: 220px;
    ">
        <span id="toast-icon" class="material-icons-round" style="font-size: 1.3rem; color: var(--success-color);">check_circle</span>
        <span id="toast-msg" style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary);"></span>
    </div>

    <div class="admin-header">
        <h1 style="font-size: 2rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-icons-round" style="color: var(--primary-color); font-size: 2.5rem;">dashboard</span>
            <span>{{ __('Admin Dashboard') }}</span>
        </h1>
        <div style="display:flex; gap:0.75rem; align-items:center;">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <span class="material-icons-round">add</span>
                <span>{{ __('Add New Dish') }}</span>
            </a>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-secondary" style="padding: 0.6rem 1rem; color: var(--danger-color); border-color: rgba(239,68,68,0.3);">
                    <span class="material-icons-round" style="font-size:1.1rem;">logout</span>
                    <span>{{ __('Sign Out') }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <!-- Card 1: Total Revenue -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--card-shadow); transition: transform 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--border-color)'">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: var(--success-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span class="material-icons-round" style="font-size: 1.75rem;">attach_money</span>
            </div>
            <div>
                <div style="font-size: 0.78rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('Total Revenue') }}</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">${{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>

        <!-- Card 2: Total Orders -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--card-shadow); transition: transform 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='rgba(99, 102, 241, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--border-color)'">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99, 102, 241, 0.12); color: #6366f1; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span class="material-icons-round" style="font-size: 1.75rem;">shopping_bag</span>
            </div>
            <div>
                <div style="font-size: 0.78rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('Total Orders') }}</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalOrdersCount }}</div>
            </div>
        </div>

        <!-- Card 3: Pending Orders -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--card-shadow); transition: transform 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='rgba(245, 158, 11, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--border-color)'">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.12); color: var(--warning-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span class="material-icons-round" style="font-size: 1.75rem;">pending_actions</span>
            </div>
            <div>
                <div style="font-size: 0.78rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('Pending Orders') }}</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $pendingOrdersCount }}</div>
            </div>
        </div>

        <!-- Card 4: Total Menu Items -->
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--card-shadow); transition: transform 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='rgba(236, 72, 153, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--border-color)'">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(236, 72, 153, 0.12); color: #ec4899; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span class="material-icons-round" style="font-size: 1.75rem;">restaurant_menu</span>
            </div>
            <div>
                <div style="font-size: 0.78rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">{{ __('Total Dishes') }}</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem;">{{ $totalProductsCount }}</div>
            </div>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="tabs-container">
        <div class="tab-nav" role="tablist">
            <button class="tab-btn active" onclick="switchTab('products-tab')" id="btn-products-tab" role="tab" aria-selected="true" aria-controls="products-tab">
                <span class="material-icons-round" style="vertical-align: middle; margin-right: 0.25rem;">restaurant_menu</span>
                <span>{{ __('Menu Items') }}</span>
            </button>
            <button class="tab-btn" onclick="switchTab('orders-tab')" id="btn-orders-tab" role="tab" aria-selected="false" aria-controls="orders-tab">
                <span class="material-icons-round" style="vertical-align: middle; margin-right: 0.25rem;">shopping_basket</span>
                <span>{{ __('Orders') }}</span>
            </button>
        </div>

        <!-- Products Tab Content -->
        <div id="products-tab" class="tab-content active" role="tabpanel" aria-labelledby="btn-products-tab">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Dish Name') }}</th>
                            <th>{{ __('Prep Time') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Available') }}</th>
                            <th style="width: 200px;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" class="table-image" alt="{{ $product->name }}">
                                    @else
                                        <div style="width: 50px; height: 50px; border-radius: 8px; background: #1e293b; display: flex; align-items: center; justify-content: center;">
                                            <span class="material-icons-round">image</span>
                                        </div>
                                    @endif
                                </td>
                                <td style="font-weight: 700;">{{ $product->name }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.85rem; color: var(--text-secondary);">
                                        <span class="material-icons-round" style="font-size: 1rem; color: var(--primary-color);">schedule</span>
                                        <span>{{ $product->prep_time_minutes ?? 15 }} {{ __('Mins') }}</span>
                                    </div>
                                </td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    @if($product->stock > 0)
                                        <span class="stock-badge stock-in">{{ $product->stock }} {{ $product->stock > 1 ? __('dishes') : __('dish') }}</span>
                                    @else
                                        <span class="stock-badge stock-out">{{ __('Out of Stock') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary btn-sm" style="padding: 0.4rem; color: var(--primary-color); border-color: rgba(99,102,241,0.2);" aria-label="{{ __('Edit') }}">
                                            <span class="material-icons-round" style="font-size: 1.15rem;">edit</span>
                                        </a>
                                        
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this dish?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.4rem; color: var(--danger-color); border-color: rgba(239,68,68,0.2);" aria-label="{{ __('Delete') }}">
                                                <span class="material-icons-round" style="font-size: 1.15rem;">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                    <span class="material-icons-round" style="font-size: 3rem; margin-bottom: 0.5rem;">inventory</span>
                                    <div>{{ __('No products found') }}</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Orders Tab Content -->
        <div id="orders-tab" class="tab-content" role="tabpanel" aria-labelledby="btn-orders-tab">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('Order ID') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Items Ordered') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th style="text-align:center;">💳 {{ __('Payment') }}</th>
                            <th style="text-align:center;">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td style="font-weight: 700;">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $order->customer_name }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $order->customer_phone }} | {{ $order->customer_email }}</div>
                                    <div style="font-size: 0.8rem; color: var(--text-secondary); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $order->customer_address }}</div>
                                </td>
                                <td>
                                    <ul style="padding-left: 0.5rem; font-size: 0.85rem; color: var(--text-secondary); list-style: none;">
                                        @foreach($order->items as $item)
                                            <li style="margin-bottom: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 0.25rem;">
                                                <div style="font-weight: 600; color: var(--text-primary);">
                                                    @if($item->product)
                                                        {{ $item->product->name }} (x{{ $item->quantity }})
                                                    @else
                                                        {{ __('Deleted Product') }} (x{{ $item->quantity }})
                                                    @endif
                                                </div>
                                                @if(!empty($item->options))
                                                    <div style="font-size: 0.75rem; color: var(--primary-color); display: flex; flex-direction: column; gap: 0.05rem; margin-top: 0.1rem;">
                                                        @if(isset($item->options['spice']))
                                                            <span>{{ __('Spice Level') }}: {{ __($item->options['spice']) }}</span>
                                                        @endif
                                                        @if(isset($item->options['sweetness']))
                                                            <span>{{ __('Sweetness Level') }}: {{ $item->options['sweetness'] }}</span>
                                                        @endif
                                                        @if(isset($item->options['ice']))
                                                            <span>{{ __('Ice Level') }}: {{ $item->options['ice'] }}</span>
                                                        @endif
                                                        @if(isset($item->options['addons']) && count($item->options['addons']) > 0)
                                                            <span>{{ __('Add-ons') }}: 
                                                                @foreach($item->options['addons'] as $addon)
                                                                    {{ $item->product ? $item->product->getAddonLabel($addon) : __($addon) }}{{ !$loop->last ? ', ' : '' }}
                                                                @endforeach
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td style="font-weight: 700; color: var(--primary-color);">${{ number_format($order->total_amount, 2) }}</td>
                                <td style="font-size: 0.85rem;">{{ $order->created_at->format('d-M-Y H:i') }}</td>
                                <td>
                                    {{-- Status Badge (Read-Only/Auto) --}}
                                    <div style="margin-bottom: 0.4rem;">
                                        <span class="status-pill status-{{ $order->status }}" id="status-badge-{{ $order->id }}" style="display: block; text-align: center; font-weight: 700; text-transform: uppercase;">
                                            @if($order->status === 'pending')
                                                ⏳ {{ __('pending') }}
                                            @elseif($order->status === 'confirmed')
                                                ✅ {{ __('confirmed') }}
                                            @elseif($order->status === 'preparing')
                                                🍳 {{ __('preparing') }}
                                            @elseif($order->status === 'out_for_delivery')
                                                🛵 {{ __('out_for_delivery') }}
                                            @elseif($order->status === 'delivered')
                                                🎉 {{ __('delivered') }}
                                            @else
                                                📦 {{ __($order->status) }}
                                            @endif
                                        </span>
                                    </div>

                                    {{-- Delivery Duration Input --}}
                                    <div style="display: flex; gap: 0.25rem; align-items: center; width: 100%;">
                                        <input
                                            type="number"
                                            id="delivery-minutes-input-{{ $order->id }}"
                                            class="delivery-minutes-input"
                                            value="{{ $order->estimated_delivery_minutes }}"
                                            placeholder="⏱️ {{ __('Mins') }}"
                                            min="1"
                                            style="
                                                border: 1.5px solid var(--border-color);
                                                border-radius: 20px;
                                                padding: 0.3rem 0.5rem 0.3rem 0.75rem;
                                                font-size: 0.78rem; font-weight: 700;
                                                outline: none;
                                                transition: all 0.2s ease;
                                                width: 100%;
                                                background-color: rgba(255,255,255,0.03);
                                                color: var(--text-primary);
                                                text-align: center;
                                            "
                                        >
                                        <button
                                            class="delivery-minutes-save-btn btn-secondary btn-sm"
                                            data-order-id="{{ $order->id }}"
                                            data-url="{{ route('admin.orders.updateDeliveryMinutes', $order->id) }}"
                                            data-csrf="{{ csrf_token() }}"
                                            style="
                                                padding: 0.3rem 0.5rem;
                                                border-radius: 50%;
                                                width: 26px; height: 26px;
                                                display: inline-flex; align-items: center; justify-content: center;
                                                flex-shrink: 0;
                                                cursor: pointer;
                                            "
                                            title="{{ __('Save') }}"
                                        >
                                            <span class="material-icons-round" style="font-size: 0.95rem; color: var(--success-color);">check</span>
                                        </button>
                                    </div>
                                </td>
                                {{-- Payment Proof --}}
                                <td style="text-align: center; min-width: 110px;">
                                    @if($order->payment_proof)
                                        <div style="position:relative; display:inline-block;">
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="proof"
                                                    style="width:56px; height:56px; object-fit:cover; border-radius:8px; border:2px solid {{ $order->payment_verified ? '#10b981' : '#f59e0b' }}; cursor:pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                            </a>
                                            @if(!$order->payment_verified)
                                                <form method="POST" action="{{ route('admin.orders.verifyPayment', $order->id) }}" style="margin:0;">
                                                    @csrf
                                                    <button type="submit" title="{{ __('Verify Payment') }}" style="
                                                        margin-top:0.3rem; display:block; width:100%;
                                                        background: linear-gradient(135deg,#10b981,#059669);
                                                        color:#fff; border:none; border-radius:6px;
                                                        padding:0.2rem 0.4rem; font-size:0.68rem;
                                                        font-weight:700; cursor:pointer; font-family:inherit;
                                                    ">✓ {{ __('Verify') }}</button>
                                                </form>
                                            @else
                                                <div style="font-size:0.68rem; color:#10b981; font-weight:700; margin-top:0.2rem;">✓ {{ __('Verified') }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <div style="display:inline-block;">
                                            @if(!$order->payment_verified)
                                                <form method="POST" action="{{ route('admin.orders.verifyPayment', $order->id) }}" style="margin:0;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-secondary btn-sm" title="{{ __('Verify Payment') }}" style="
                                                        padding: 0.25rem 0.6rem; font-size: 0.72rem; font-weight: 700;
                                                        border-color: rgba(16,185,129,0.3); color: #10b981; background: transparent;
                                                    ">
                                                        <span>✓ {{ __('Verify') }}</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span style="font-size:0.75rem; color:#10b981; font-weight:700;">✓ {{ __('Verified') }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                {{-- Actions --}}
                                <td style="text-align: center;">
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}')" style="margin: 0; display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.4rem; border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; background: var(--danger-color); color: #fff; cursor: pointer; border: none;" title="{{ __('Delete Order') }}">
                                            <span class="material-icons-round" style="font-size: 1.15rem;">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                                    <span class="material-icons-round" style="font-size: 3rem; margin-bottom: 0.5rem;">receipt_long</span>
                                    <div>{{ __('No orders found') }}</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<style>
    .status-pending          { color: #f59e0b !important; border-color: #f59e0b !important; background-color: rgba(245,158,11,0.1) !important; }
    .status-confirmed        { color: #3b82f6 !important; border-color: #3b82f6 !important; background-color: rgba(59,130,246,0.1) !important; }
    .status-preparing        { color: #f97316 !important; border-color: #f97316 !important; background-color: rgba(249,115,22,0.1) !important; }
    .status-out_for_delivery { color: #a855f7 !important; border-color: #a855f7 !important; background-color: rgba(168,85,247,0.1) !important; }
    .status-delivered        { color: #10b981 !important; border-color: #10b981 !important; background-color: rgba(16,185,129,0.1) !important; }
</style>
<script>
    /* ── Tab switcher ── */
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
        });
        document.getElementById(tabId).classList.add('active');
        const btn = document.getElementById('btn-' + tabId);
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
    }

    /* ── Apply colour class to a select ── */
    function applyStatusColour(select, status) {
        select.className = 'order-status-select status-' + status;
    }

    /* ── Toast helper ── */
    let toastTimer = null;
    function showToast(msg, ok) {
        const toast = document.getElementById('status-toast');
        const icon  = document.getElementById('toast-icon');
        const text  = document.getElementById('toast-msg');
        icon.textContent = ok ? 'check_circle' : 'error';
        icon.style.color = ok ? 'var(--success-color)' : 'var(--danger-color)';
        text.textContent = msg;
        toast.style.opacity   = '1';
        toast.style.transform = 'translateY(0)';
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.style.opacity   = '0';
            toast.style.transform = 'translateY(-12px)';
        }, 2800);
    }

    /* ── Initialise selects on page load ── */
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.delivery-minutes-save-btn').forEach(btn => {
            btn.addEventListener('click', async function () {
                const orderId = this.dataset.orderId;
                const url = this.dataset.url;
                const csrf = this.dataset.csrf;
                
                const inputEl = document.getElementById(`delivery-minutes-input-${orderId}`);
                if (!inputEl) return;
                const val = inputEl.value;

                try {
                    const res = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ delivery_minutes: val || null }),
                    });
                    if (!res.ok) throw new Error('Server error');
                    const data = await res.json();
                    showToast(data.message || '{{ __("Duration updated!") }}', true);

                    if (data.status) {
                        const statusBadge = document.getElementById(`status-badge-${orderId}`);
                        if (statusBadge) {
                            const statusLabels = {
                                'pending': '⏳ {{ __("pending") }}',
                                'confirmed': '✅ {{ __("confirmed") }}',
                                'preparing': '🍳 {{ __("preparing") }}',
                                'out_for_delivery': '🛵 {{ __("out_for_delivery") }}',
                                'delivered': '🎉 {{ __("delivered") }}'
                            };
                            statusBadge.textContent = statusLabels[data.status] || data.status;
                            statusBadge.className = `status-pill status-${data.status}`;
                        }
                    }
                } catch (err) {
                    showToast('{{ __("Update failed. Please try again.") }}', false);
                }
            });
        });

        // Also save on pressing Enter inside the input field
        document.querySelectorAll('.delivery-minutes-input').forEach(input => {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const orderId = this.id.replace('delivery-minutes-input-', '');
                    const saveBtn = document.querySelector(`.delivery-minutes-save-btn[data-order-id="${orderId}"]`);
                    if (saveBtn) saveBtn.click();
                }
            });
        });
    });
</script>
@endsection
