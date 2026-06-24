@extends('layouts.app')

@section('title', __('My Orders') . ' — Happy Meal')

@section('content')
    <h1 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
        <span class="material-icons-round" style="color: var(--primary-color);">receipt_long</span>
        <span>{{ __('My Orders') }}</span>
    </h1>

    @forelse($orders as $order)
        <div data-order-id="{{ $order->id }}" data-order-active="{{ $order->status !== 'delivered' ? 'true' : 'false' }}" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.25rem; box-shadow: var(--card-shadow);">

            {{-- Order Header --}}
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                <div>
                    <div style="font-size: 1rem; font-weight: 800; color: var(--text-primary);">
                        {{ __('Order ID') }}: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.2rem;">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 1rem;">
                    {{-- Status badge --}}
                    @php
                        $statusColors = [
                            'pending'          => '#f59e0b',
                            'confirmed'        => '#3b82f6',
                            'preparing'        => '#f97316',
                            'out_for_delivery' => '#a855f7',
                            'delivered'        => '#10b981',
                        ];
                        $statusEmojis = [
                            'pending'          => '⏳',
                            'confirmed'        => '✅',
                            'preparing'        => '🍳',
                            'out_for_delivery' => '🛵',
                            'delivered'        => '🎉',
                        ];
                        $color = $statusColors[$order->status] ?? '#6366f1';
                        $emoji = $statusEmojis[$order->status] ?? '📦';
                    @endphp
                    <span id="status-badge-{{ $order->id }}" style="
                        background: {{ $color }}18;
                        color: {{ $color }};
                        border: 1px solid {{ $color }}44;
                        border-radius: 20px;
                        padding: 0.3rem 0.9rem;
                        font-size: 0.8rem;
                        font-weight: 700;
                    ">{{ $emoji }} {{ __($order->status) }}</span>

                    <div style="font-size: 1.1rem; font-weight: 800; color: var(--primary-color);">
                        ${{ number_format($order->total_amount, 2) }}
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem;">
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset($item->product->image) }}" alt=""
                                    style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border-color);">
                            @else
                                <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,0.05); display:flex; align-items:center; justify-content:center;">
                                    <span class="material-icons-round" style="font-size:1rem; color:var(--text-secondary);">restaurant</span>
                                </div>
                            @endif
                            <div>
                                <div style="font-weight: 600;">
                                    {{ $item->product ? $item->product->name : __('Deleted Product') }}
                                    <span style="color:var(--text-secondary);">(x{{ $item->quantity }})</span>
                                </div>
                                @if(!empty($item->options))
                                    <div style="font-size:0.75rem; color: var(--primary-color);">
                                        @if(isset($item->options['spice'])) {{ __($item->options['spice']) }} @endif
                                        @if(isset($item->options['sweetness'])) · {{ $item->options['sweetness'] }} @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div style="font-weight: 700;">${{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Mini Tracker --}}
            @php
                $steps = [
                    ['key' => 'pending',          'label' => __('Received')],
                    ['key' => 'confirmed',        'label' => __('Confirmed')],
                    ['key' => 'preparing',        'label' => __('Preparing')],
                    ['key' => 'out_for_delivery', 'label' => __('Delivering')],
                    ['key' => 'delivered',        'label' => __('Delivered')],
                ];
                $statusOrder = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
                $curIdx = array_search($order->status, $statusOrder);
                if ($curIdx === false) $curIdx = 0;
            @endphp
            <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                {{-- Row 1: Circles & Lines --}}
                <div class="mini-tracker-steps">
                    
                    {{-- Connecting line background --}}
                    <div class="mini-tracker-line-bg">
                        {{-- Filled progress line --}}
                        @php
                            $progressPct = $curIdx > 0 ? ($curIdx / (count($steps) - 1)) * 100 : 0;
                        @endphp
                        <div id="mini-progress-bar-fill-{{ $order->id }}" class="mini-tracker-line-fill" style="--progress-pct: {{ $progressPct }}%;"></div>
                    </div>
                    
                    @foreach($steps as $si => $step)
                        @php
                            $done   = $si < $curIdx;
                            $active = $si === $curIdx;
                            
                            $circleColor = $done ? '#10b981' : ($active ? 'var(--primary-color)' : 'var(--border-color)');
                            $circleBg    = $done ? '#10b981' : ($active ? 'rgba(99,102,241,0.15)' : 'var(--bg-input)');
                        @endphp
                        <div class="mini-tracker-circle-wrap">
                            {{-- Step Circle --}}
                            <div id="mini-step-circle-{{ $order->id }}-{{ $si }}" class="mini-tracker-circle" style="
                                border-color: {{ $circleColor }};
                                background-color: {{ $circleBg }};
                            ">
                                <div id="mini-step-icon-{{ $order->id }}-{{ $si }}" style="display:flex; align-items:center; justify-content:center;">
                                    @if($done)
                                        <span style="font-size:9px; color:#fff; font-weight:900; line-height:1;">✓</span>
                                    @elseif($active)
                                        <div class="mini-tracker-active-dot"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Row 2: Labels --}}
                <div class="mini-tracker-labels-row">
                    @foreach($steps as $si => $step)
                        @php
                            $done   = $si < $curIdx;
                            $active = $si === $curIdx;
                            $labelColor  = $done ? '#10b981' : ($active ? 'var(--text-primary)' : 'var(--text-secondary)');
                        @endphp
                        <div class="mini-tracker-label-wrap">
                            <span id="mini-step-label-{{ $order->id }}-{{ $si }}" class="mini-tracker-label" style="color: {{ $labelColor }}; font-weight: {{ $active ? '700' : '500' }};">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div style="text-align:center; font-size:0.78rem; color:var(--text-secondary); margin-top:0.4rem;">
                    {{ __('Current Status') }} : <strong id="current-status-val-{{ $order->id }}" style="color:var(--text-primary);">{{ __($order->status) }}</strong>
                </div>

                @php
                    $remainingSeconds = 0;
                    if ($order->estimated_delivery_minutes) {
                        $expiryTimestamp = $order->created_at->timestamp + ($order->estimated_delivery_minutes * 60);
                        $remainingSeconds = max(0, $expiryTimestamp - now()->timestamp);
                    }
                @endphp
                <div id="countdown-container-{{ $order->id }}" class="order-countdown-container" style="display: {{ ($order->estimated_delivery_minutes && $order->status !== 'delivered') ? 'flex' : 'none' }}; align-items: center; justify-content: center; gap: 0.3rem; font-size: 0.78rem; color: var(--text-secondary); margin-top: 0.35rem;" data-remaining-seconds="{{ $remainingSeconds }}" data-total-seconds="{{ $order->estimated_delivery_minutes ? $order->estimated_delivery_minutes * 60 : 0 }}">
                    <span class="material-icons-round" style="font-size: 0.95rem; color: var(--primary-color);">schedule</span>
                    <span>{{ __('Estimated Delivery') }}:</span>
                    <strong id="countdown-timer-{{ $order->id }}" style="color: var(--primary-color); font-family: monospace; font-size: 0.85rem;">
                        @php
                            $mins = floor($remainingSeconds / 60);
                            $secs = $remainingSeconds % 60;
                            $minsStr = str_pad($mins, 2, '0', STR_PAD_LEFT);
                            $secsStr = str_pad($secs, 2, '0', STR_PAD_LEFT);
                        @endphp
                        {{ $minsStr }} {{ __('Mins') }} {{ $secsStr }} {{ __('Secs') }}
                    </strong>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align:center; padding: 4rem 2rem; color:var(--text-secondary);">
            <span class="material-icons-round" style="font-size:4rem; margin-bottom:1rem; display:block;">inbox</span>
            <div style="font-size:1.2rem; font-weight:700; margin-bottom:0.5rem;">{{ __('No orders yet') }}</div>
            <p style="margin-bottom:1.5rem;">{{ __('You have not ordered any dishes yet.') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <span class="material-icons-round">restaurant_menu</span>
                {{ __('Go to Menu') }}
            </a>
        </div>
    @endforelse
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const activeOrderElements = document.querySelectorAll('[data-order-active="true"]');
        
        const stepsKeys = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')} {{ __('Mins') }} ${secs.toString().padStart(2, '0')} {{ __('Secs') }}`;
        }

        function translateStatus(status) {
            const translations = {
                'pending': '{{ __("pending") }}',
                'confirmed': '{{ __("confirmed") }}',
                'preparing': '{{ __("preparing") }}',
                'out_for_delivery': '{{ __("out_for_delivery") }}',
                'delivered': '{{ __("delivered") }}'
            };
            return translations[status] || status;
        }

        function updateOrderProgressRealtime(orderId, remaining, total) {
            const elapsed = Math.max(0, total - remaining);
            const ratio = total > 0 ? (elapsed / total) : 0;

            let curIdx = 0;
            let calculatedStatus = 'pending';
            if (ratio >= 1.0) {
                calculatedStatus = 'delivered';
                curIdx = 4;
            } else if (ratio >= 0.75) {
                calculatedStatus = 'out_for_delivery';
                curIdx = 3;
            } else if (ratio >= 0.30) {
                calculatedStatus = 'preparing';
                curIdx = 2;
            } else if (ratio >= 0.10) {
                calculatedStatus = 'confirmed';
                curIdx = 1;
            } else {
                calculatedStatus = 'pending';
                curIdx = 0;
            }

            // 1. Update text badge
            const statusBadge = document.getElementById(`status-badge-${orderId}`);
            if (statusBadge) {
                const statusColors = {
                    'pending': '#f59e0b',
                    'confirmed': '#3b82f6',
                    'preparing': '#f97316',
                    'out_for_delivery': '#a855f7',
                    'delivered': '#10b981',
                };
                const statusEmojis = {
                    'pending': '⏳',
                    'confirmed': '✅',
                    'preparing': '🍳',
                    'out_for_delivery': '🛵',
                    'delivered': '🎉',
                };
                const color = statusColors[calculatedStatus] || '#6366f1';
                const emoji = statusEmojis[calculatedStatus] || '📦';

                statusBadge.style.color = color;
                statusBadge.style.backgroundColor = color + '18';
                statusBadge.style.borderColor = color + '44';
                statusBadge.innerHTML = `${emoji} ${translateStatus(calculatedStatus)}`;
            }

            // 2. Update current status text at the bottom
            const curStatusVal = document.getElementById(`current-status-val-${orderId}`);
            if (curStatusVal) {
                curStatusVal.textContent = translateStatus(calculatedStatus);
            }

            // 3. Update progress line width
            const progressBar = document.getElementById(`mini-progress-bar-fill-${orderId}`);
            if (progressBar) {
                const progressPct = ratio * 100;
                progressBar.style.setProperty('--progress-pct', `${progressPct}%`);
            }

            // 4. Update circles
            stepsKeys.forEach((key, si) => {
                const circle = document.getElementById(`mini-step-circle-${orderId}-${si}`);
                const icon = document.getElementById(`mini-step-icon-${orderId}-${si}`);
                const label = document.getElementById(`mini-step-label-${orderId}-${si}`);

                if (circle && icon) {
                    const done = si < curIdx;
                    const active = si === curIdx;

                    if (done) {
                        circle.style.borderColor = '#10b981';
                        circle.style.backgroundColor = '#10b981';
                        icon.innerHTML = '<span style="font-size:9px; color:#fff; font-weight:900; line-height:1;">✓</span>';
                    } else if (active) {
                        circle.style.borderColor = 'var(--primary-color)';
                        circle.style.backgroundColor = 'rgba(99,102,241,0.15)';
                        icon.innerHTML = '<div class="mini-tracker-active-dot"></div>';
                    } else {
                        circle.style.borderColor = 'var(--border-color)';
                        circle.style.backgroundColor = 'var(--bg-input)';
                        icon.innerHTML = '';
                    }
                }

                if (label) {
                    const done = si < curIdx;
                    const active = si === curIdx;
                    if (done) {
                        label.style.color = '#10b981';
                        label.style.fontWeight = '500';
                    } else if (active) {
                        label.style.color = 'var(--text-primary)';
                        label.style.fontWeight = '700';
                    } else {
                        label.style.color = 'var(--text-secondary)';
                        label.style.fontWeight = '500';
                    }
                }
            });

            // 5. If delivered, hide countdown, mark order container as inactive
            if (calculatedStatus === 'delivered') {
                const countdownContainer = document.getElementById(`countdown-container-${orderId}`);
                if (countdownContainer) {
                    countdownContainer.style.display = 'none';
                }
                const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
                if (orderCard) {
                    orderCard.dataset.orderActive = "false";
                }
            }
        }

        // Initialize countdown text and progress indicators on page load
        document.querySelectorAll('.order-countdown-container').forEach(container => {
            const orderId = container.id.replace('countdown-container-', '');
            const remaining = parseInt(container.dataset.remainingSeconds) || 0;
            const total = parseInt(container.dataset.totalSeconds) || 0;
            const timerEl = document.getElementById(`countdown-timer-${orderId}`);
            if (timerEl) {
                timerEl.textContent = formatTime(remaining);
            }
            // Draw immediate progress
            updateOrderProgressRealtime(orderId, remaining, total);
        });

        // Live countdown decrementing every second
        setInterval(() => {
            document.querySelectorAll('.order-countdown-container').forEach(container => {
                const orderId = container.id.replace('countdown-container-', '');
                const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
                if (orderCard && orderCard.dataset.orderActive !== "true") return;

                let remaining = parseInt(container.dataset.remainingSeconds) || 0;
                const total = parseInt(container.dataset.totalSeconds) || 0;

                if (remaining > 0) {
                    remaining--;
                    container.dataset.remainingSeconds = remaining;
                    const timerEl = document.getElementById(`countdown-timer-${orderId}`);
                    if (timerEl) {
                        timerEl.textContent = formatTime(remaining);
                    }
                } else {
                    const timerEl = document.getElementById(`countdown-timer-${orderId}`);
                    if (timerEl) {
                        timerEl.textContent = formatTime(0);
                    }
                }
                updateOrderProgressRealtime(orderId, remaining, total);
            });
        }, 1000);

        if (activeOrderElements.length > 0) {
            // Poll every 5 seconds
            setInterval(async () => {
                activeOrderElements.forEach(async (el) => {
                    if (el.dataset.orderActive !== "true") return;
                    const orderId = el.dataset.orderId;
                    const statusUrl = `/orders/${orderId}/status`;

                    try {
                        const res = await fetch(statusUrl);
                        if (!res.ok) throw new Error('Network error');
                        const data = await res.json();

                        // Update UI
                        updateMiniTracker(orderId, data);
                    } catch (e) {
                        console.error("Failed to poll order " + orderId, e);
                    }
                });
            }, 5000);
        }

        function updateMiniTracker(orderId, data) {
            const countdownContainer = document.getElementById(`countdown-container-${orderId}`);
            if (countdownContainer) {
                if (data.estimated_delivery_minutes && data.status !== 'delivered') {
                    countdownContainer.style.display = 'flex';
                    countdownContainer.dataset.remainingSeconds = data.remaining_seconds;
                    countdownContainer.dataset.totalSeconds = data.estimated_delivery_minutes * 60;
                    
                    const timerEl = document.getElementById(`countdown-timer-${orderId}`);
                    if (timerEl) {
                        timerEl.textContent = formatTime(data.remaining_seconds);
                    }
                } else {
                    countdownContainer.style.display = 'none';
                    countdownContainer.dataset.remainingSeconds = 0;
                    countdownContainer.dataset.totalSeconds = 0;
                }
            }

            const remaining = parseInt(countdownContainer ? countdownContainer.dataset.remainingSeconds : 0) || 0;
            const total = parseInt(countdownContainer ? countdownContainer.dataset.totalSeconds : 0) || 0;
            updateOrderProgressRealtime(orderId, remaining, total);

            // If delivered, mark as inactive
            if (data.status === 'delivered') {
                const el = document.querySelector(`[data-order-id="${orderId}"]`);
                if (el) el.dataset.orderActive = "false";
            }
        }
    });
</script>
@endsection
