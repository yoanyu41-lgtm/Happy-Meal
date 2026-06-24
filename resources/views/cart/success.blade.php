@extends('layouts.app')

@section('title', __('Order Successful!') . ' - Happy Meal')

@section('content')
    <div style="max-width: 700px; margin: 3rem auto;">

        {{-- ════════════════════════════════════════════════
             SUCCESS CARD
        ════════════════════════════════════════════════ --}}
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 2.5rem 2rem; box-shadow: var(--card-shadow); text-align: center; margin-bottom: 1.5rem;">
            <!-- Icon -->
            <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.15); color: var(--success-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                <span class="material-icons-round" style="font-size: 3rem;">check_circle</span>
            </div>
            
            <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -0.5px;">{{ __('Order Successful!') }}</h1>
            <p style="color: var(--text-secondary); margin-bottom: 2rem;">{{ __('Thank you for your order. Your order has been recorded in our system.') }}</p>
            
            <!-- Order Info Table -->
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; text-align: left; margin-bottom: 2rem; font-size: 0.95rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.25rem;">
                    <span class="material-icons-round" style="font-size: 1.25rem; color: var(--primary-color);">receipt</span>
                    <span>{{ __('Order Summary') }}</span>
                </h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">{{ __('Order ID') }}</span>
                    <strong style="color: var(--text-primary);">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">{{ __('Customer') }}</span>
                    <span style="font-weight: 600;">{{ $order->customer_name }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary);">{{ __('Phone') }}</span>
                    <span>{{ $order->customer_phone }}</span>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                    <span style="color: var(--text-secondary);">{{ __('Address') }}</span>
                    <span style="text-align: right; max-width: 60%; font-weight: 500;">{{ $order->customer_address }}</span>
                </div>

                <!-- Dishes Ordered -->
                <div style="border-top: 1px dashed var(--border-color); padding-top: 1rem; margin-top: 1rem; margin-bottom: 1rem;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text-secondary);">{{ __('Items Ordered') }}:</h4>
                    <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($order->items as $item)
                            <li style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <div>
                                    <span>{{ $item->product ? $item->product->name : __('Deleted Product') }} <strong>(x{{ $item->quantity }})</strong></span>
                                    @if(!empty($item->options))
                                        <div style="font-size: 0.75rem; color: var(--primary-color); display: flex; flex-direction: column; margin-top: 0.15rem; gap: 0.05rem;">
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
                                </div>
                                <span style="font-weight: 600;">${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div style="border-top: 1px dashed var(--border-color); padding-top: 1rem; display: flex; justify-content: space-between; font-weight: 700; font-size: 1.1rem;">
                    <span>{{ __('Total Paid') }}</span>
                    <span style="color: var(--primary-color);">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="flex-grow: 1;">
                    <span class="material-icons-round">shopping_bag</span>
                    <span>{{ __('Return to Home') }}</span>
                </a>
                @auth
                <a href="{{ route('customer.orders') }}" class="btn btn-secondary" style="flex-grow: 1;">
                    <span class="material-icons-round">receipt_long</span>
                    <span>{{ __('My Orders') }}</span>
                </a>
                @endauth
            </div>
        </div>

        {{-- ════ Payment Status Card ════ --}}
        <div id="payment-proof-card" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 1.75rem; box-shadow: var(--card-shadow); margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <span class="material-icons-round" style="color: var(--primary-color);">payments</span>
                {{ __('Payment Status') }}
            </h3>

            {{-- Verified Badge --}}
            <div id="payment-verified-container" style="display: {{ $order->payment_verified ? 'flex' : 'none' }}; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); border-radius: 12px; padding: 0.75rem 1rem; align-items: center; gap: 0.6rem;">
                <span class="material-icons-round" style="color: #10b981;">verified</span>
                <div>
                    <div style="font-weight: 700; color: #10b981; font-size: 0.9rem;">{{ __('Payment Verified!') }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ __('Admin has confirmed your payment.') }}</div>
                </div>
            </div>

            {{-- Unverified/Verifying Badge --}}
            <div id="payment-unverified-container" style="display: {{ !$order->payment_verified ? 'flex' : 'none' }}; background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3); border-radius: 12px; padding: 0.75rem 1rem; align-items: center; gap: 0.6rem;">
                <span class="material-icons-round" style="color: #f59e0b;">hourglass_top</span>
                <div>
                    <div style="font-weight: 700; color: #f59e0b; font-size: 0.9rem;">{{ __('Verifying Payment...') }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ __('Admin is reviewing your payment.') }}</div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             ESTIMATED DELIVERY COUNTDOWN TIMER CARD
        ════════════════════════════════════════════════ --}}
        @php
            $remainingSeconds = 0;
            if ($order->estimated_delivery_minutes) {
                $expiryTimestamp = $order->created_at->timestamp + ($order->estimated_delivery_minutes * 60);
                $remainingSeconds = max(0, $expiryTimestamp - now()->timestamp);
            }
        @endphp
        <div id="countdown-container" style="
            display: {{ $order->estimated_delivery_minutes ? 'flex' : 'none' }};
            align-items: center; justify-content: center; flex-direction: column;
            background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(16,185,129,0.04));
            border: 1.5px solid rgba(99,102,241,0.22);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            box-shadow: var(--card-shadow);
        ">
            <div style="display: flex; align-items: center; gap: 0.4rem; color: var(--text-secondary); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                <span class="material-icons-round" style="color: var(--primary-color); font-size: 1.15rem;">schedule</span>
                <span>{{ __('Estimated Delivery') }}</span>
            </div>
            
            <div id="countdown-timer" style="font-size: 2.5rem; font-weight: 900; letter-spacing: 1px; color: var(--primary-color); font-family: monospace;">
                @php
                    $mins = floor($remainingSeconds / 60);
                    $secs = $remainingSeconds % 60;
                    $minsStr = str_pad($mins, 2, '0', STR_PAD_LEFT);
                    $secsStr = str_pad($secs, 2, '0', STR_PAD_LEFT);
                @endphp
                {{ $minsStr }} {{ __('Mins') }} {{ $secsStr }} {{ __('Secs') }}
            </div>

            <div id="countdown-status" style="font-size: 0.82rem; color: var(--text-secondary); margin-top: 0.4rem; font-weight: 500;">
                {{ __('Your order will arrive shortly') }}
            </div>
        </div>

        {{-- ════════════════════════════════════════════════
             ORDER TRACKING PROGRESS BAR
        ════════════════════════════════════════════════ --}}
        @php
            $steps = [
                ['key' => 'pending',          'label' => __('Order Received'),   'icon' => 'receipt_long',       'desc' => __('We received your order and will confirm it shortly.')],
                ['key' => 'confirmed',        'label' => __('Order Confirmed'),  'icon' => 'thumb_up',           'desc' => __('Your order is confirmed! Kitchen is getting ready.')],
                ['key' => 'preparing',        'label' => __('Food Preparing'),   'icon' => 'restaurant',         'desc' => __('Our chef is cooking your delicious food!')],
                ['key' => 'out_for_delivery', 'label' => __('Out for Delivery'), 'icon' => 'delivery_dining',    'desc' => __('Your food is on the way to your location!')],
                ['key' => 'delivered',        'label' => __('Order Delivered'),  'icon' => 'celebration',        'desc' => __('Your food has been delivered. Enjoy your meal!')],
            ];
            $statusOrder = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
            $currentIndex = array_search($order->status, $statusOrder);
            if ($currentIndex === false) $currentIndex = 0;
        @endphp

        <div id="order-tracker" style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 2rem 1.5rem; box-shadow: var(--card-shadow);">
            
            <!-- Tracker Header -->
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem;">
                <span class="material-icons-round" style="color: var(--primary-color); font-size: 1.5rem;">local_shipping</span>
                <h2 style="font-size: 1.2rem; font-weight: 800; margin: 0;">{{ __('Order Tracking') }}</h2>
                <span style="margin-left: auto; font-size: 0.8rem; color: var(--text-secondary); background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 20px; padding: 0.2rem 0.7rem;">
                    #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                </span>
            </div>

            <!-- Steps Row -->
            <div class="tracker-card-body" style="margin-bottom: 1.5rem;">
                {{-- Row 1: Circles & Lines --}}
                <div class="tracker-steps">
                    {{-- Connecting line background --}}
                    <div class="tracker-line-bg">
                        {{-- Filled progress line --}}
                        @php
                            $progressPct = $currentIndex > 0 ? ($currentIndex / (count($steps) - 1)) * 100 : 0;
                        @endphp
                        <div id="progress-bar-fill" class="tracker-line-fill" style="--progress-pct: {{ $progressPct }}%;"></div>
                    </div>

                    @foreach($steps as $i => $step)
                        @php
                            $isDone   = $i < $currentIndex;
                            $isActive = $i === $currentIndex;
                            
                            if ($isDone)        { $circleColor = '#10b981'; $circleBg = 'rgba(16,185,129,0.15)'; $iconColor = '#10b981'; }
                            elseif ($isActive)  { $circleColor = 'var(--primary-color)'; $circleBg = 'rgba(99,102,241,0.15)'; $iconColor = 'var(--primary-color)'; }
                            else               { $circleColor = 'var(--border-color)'; $circleBg = 'var(--bg-input)'; $iconColor = 'var(--text-secondary)'; }
                        @endphp
                        <div class="tracker-step-item-wrap">
                            {{-- Step Circle --}}
                            <div id="step-circle-{{ $i }}" class="tracker-circle {{ $isActive ? 'tracker-pulse' : '' }}" style="
                                background: {{ $circleBg }};
                                border-color: {{ $circleColor }};
                                box-shadow: {{ $isActive ? '0 0 0 6px rgba(99,102,241,0.12)' : 'none' }};
                            ">
                                <div id="step-circle-icon-{{ $i }}" style="display: flex; align-items: center; justify-content: center;">
                                    @if($isDone)
                                        <span class="material-icons-round" style="font-size: 1.2rem; color: #10b981;">check</span>
                                    @else
                                        <span class="material-icons-round" style="font-size: 1.2rem; color: {{ $iconColor }};">{{ $step['icon'] }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Mobile-only Label Container --}}
                            <div class="tracker-mobile-label-container">
                                <div id="mobile-step-label-{{ $i }}" class="tracker-mobile-label @if($isDone) done @elseif($isActive) active @endif">
                                    {{ $step['label'] }}
                                </div>
                                <div class="tracker-mobile-desc">
                                    {{ $step['desc'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Row 2: Labels --}}
                <div class="tracker-labels-row">
                    @foreach($steps as $i => $step)
                        @php
                            $isDone   = $i < $currentIndex;
                            $isActive = $i === $currentIndex;
                            $labelColor = $isDone ? '#10b981' : ($isActive ? 'var(--text-primary)' : 'var(--text-secondary)');
                        @endphp
                        <div class="tracker-label-wrap">
                            <span id="step-label-{{ $i }}" class="tracker-label" style="color: {{ $labelColor }}; font-weight: {{ $isActive ? '700' : '500' }};">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Current Status Description --}}
            <div style="
                background: rgba(99,102,241,0.07); border: 1px solid rgba(99,102,241,0.2);
                border-radius: 12px; padding: 1rem 1.25rem;
                display: flex; align-items: flex-start; gap: 0.75rem;
            ">
                <span class="material-icons-round" style="color: var(--primary-color); font-size: 1.3rem; margin-top: 0.05rem;">info</span>
                <div>
                    <div style="font-weight: 700; font-size: 0.9rem; margin-bottom: 0.2rem;">{{ __('Current Status') }}: 
                        <span id="current-status-text" style="color: var(--primary-color);">{{ __($order->status) }}</span>
                    </div>
                    <div id="current-status-desc" style="font-size: 0.85rem; color: var(--text-secondary);">
                        {{ $steps[$currentIndex]['desc'] }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
<style>
    @keyframes trackerPulse {
        0%   { box-shadow: 0 0 0 0   rgba(99,102,241,0.4); }
        70%  { box-shadow: 0 0 0 10px rgba(99,102,241,0);   }
        100% { box-shadow: 0 0 0 0   rgba(99,102,241,0);   }
    }
    .tracker-pulse {
        animation: trackerPulse 2s ease infinite;
    }
    #proof-dropzone:hover { border-color: var(--primary-color) !important; background: rgba(99,102,241,0.08) !important; }
</style>

<script>
    // ─── Payment Proof Preview ─────────────────────────────
    function previewProof(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('proof-img').src = e.target.result;
                document.getElementById('proof-preview').style.display = 'block';
                document.getElementById('proof-dropzone').style.borderColor = '#10b981';
                document.getElementById('proof-dropzone').style.background = 'rgba(16,185,129,0.05)';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearProof() {
        document.getElementById('payment_proof').value = '';
        document.getElementById('proof-preview').style.display = 'none';
        document.getElementById('proof-dropzone').style.borderColor = 'rgba(99,102,241,0.35)';
        document.getElementById('proof-dropzone').style.background = 'rgba(99,102,241,0.04)';
    }

    // Drag & drop on dropzone
    const dz = document.getElementById('proof-dropzone');
    if (dz) {
        dz.addEventListener('dragover', e => {
            e.preventDefault();
            dz.style.borderColor = 'var(--primary-color)';
            dz.style.background = 'rgba(99,102,241,0.1)';
        });
        dz.addEventListener('dragleave', () => {
            dz.style.borderColor = 'rgba(99,102,241,0.35)';
            dz.style.background = 'rgba(99,102,241,0.04)';
        });
        dz.addEventListener('drop', e => {
            e.preventDefault();
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('payment_proof').files = files;
                previewProof(document.getElementById('payment_proof'));
            }
        });
    }

    // ─── Countdown and Polling ─────────────────────────────
    let remainingSeconds = {{ $remainingSeconds }};
    let estimatedDeliveryMinutes = {{ $order->estimated_delivery_minutes ?? 30 }};
    let totalSeconds = estimatedDeliveryMinutes * 60;
    let countdownInterval = null;

    const steps = [
        { key: 'pending',          label: '{{ __("Order Received") }}',   icon: 'receipt_long',       desc: '{{ __("We received your order and will confirm it shortly.") }}' },
        { key: 'confirmed',        label: '{{ __("Order Confirmed") }}',  icon: 'thumb_up',           desc: '{{ __("Your order is confirmed! Kitchen is getting ready.") }}' },
        { key: 'preparing',        label: '{{ __("Food Preparing") }}',   icon: 'restaurant',         desc: '{{ __("Our chef is cooking your delicious food!") }}' },
        { key: 'out_for_delivery', label: '{{ __("Out for Delivery") }}', icon: 'delivery_dining',    desc: '{{ __("Your food is on the way to your location!") }}' },
        { key: 'delivered',        label: '{{ __("Order Delivered") }}',  icon: 'celebration',        desc: '{{ __("Your food has been delivered. Enjoy your meal!") }}' },
    ];

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')} {{ __('Mins') }} ${secs.toString().padStart(2, '0')} {{ __('Secs') }}`;
    }

    function updateProgressRealtime() {
        const elapsedSeconds = Math.max(0, totalSeconds - remainingSeconds);
        const ratio = totalSeconds > 0 ? (elapsedSeconds / totalSeconds) : 0;
        
        let currentIndex = 0;
        let calculatedStatus = 'pending';
        
        if (ratio >= 1.0) {
            calculatedStatus = 'delivered';
            currentIndex = 4;
        } else if (ratio >= 0.75) {
            calculatedStatus = 'out_for_delivery';
            currentIndex = 3;
        } else if (ratio >= 0.30) {
            calculatedStatus = 'preparing';
            currentIndex = 2;
        } else if (ratio >= 0.10) {
            calculatedStatus = 'confirmed';
            currentIndex = 1;
        } else {
            calculatedStatus = 'pending';
            currentIndex = 0;
        }

        // Update progress bar fill width (smoothly based on elapsed ratio)
        const progressPct = ratio * 100;
        const fillEl = document.getElementById('progress-bar-fill');
        if (fillEl) {
            fillEl.style.setProperty('--progress-pct', `${progressPct}%`);
        }

        // Update each step circle
        steps.forEach((step, i) => {
            const circle = document.getElementById(`step-circle-${i}`);
            const iconContainer = document.getElementById(`step-circle-icon-${i}`);
            
            if (!circle || !iconContainer) return;

            const isDone = i < currentIndex;
            const isActive = i === currentIndex;
            
            circle.classList.remove('tracker-pulse');

            const desktopLabel = document.getElementById(`step-label-${i}`);
            const mobileLabel = document.getElementById(`mobile-step-label-${i}`);

            if (isDone) {
                circle.style.borderColor = '#10b981';
                circle.style.background = 'rgba(16,185,129,0.15)';
                circle.style.boxShadow = 'none';
                iconContainer.innerHTML = '<span class="material-icons-round" style="font-size: 1.2rem; color: #10b981;">check</span>';
                
                if (desktopLabel) {
                    desktopLabel.style.color = '#10b981';
                    desktopLabel.style.fontWeight = '500';
                }
                if (mobileLabel) {
                    mobileLabel.className = 'tracker-mobile-label done';
                }
            } else if (isActive) {
                circle.classList.add('tracker-pulse');
                circle.style.borderColor = 'var(--primary-color)';
                circle.style.background = 'rgba(99,102,241,0.15)';
                circle.style.boxShadow = '0 0 0 6px rgba(99,102,241,0.12)';
                iconContainer.innerHTML = `<span class="material-icons-round" style="font-size: 1.2rem; color: var(--primary-color);">${step.icon}</span>`;
                
                if (desktopLabel) {
                    desktopLabel.style.color = 'var(--text-primary)';
                    desktopLabel.style.fontWeight = '700';
                }
                if (mobileLabel) {
                    mobileLabel.className = 'tracker-mobile-label active';
                }
            } else {
                circle.style.borderColor = 'var(--border-color)';
                circle.style.background = 'var(--bg-input)';
                circle.style.boxShadow = 'none';
                iconContainer.innerHTML = `<span class="material-icons-round" style="font-size: 1.2rem; color: var(--text-secondary);">${step.icon}</span>`;
                
                if (desktopLabel) {
                    desktopLabel.style.color = 'var(--text-secondary)';
                    desktopLabel.style.fontWeight = '500';
                }
                if (mobileLabel) {
                    mobileLabel.className = 'tracker-mobile-label';
                }
            }
        });

        // Update text current status
        const textStatusEl = document.getElementById('current-status-text');
        if (textStatusEl) textStatusEl.textContent = translateStatus(calculatedStatus);
        
        const descStatusEl = document.getElementById('current-status-desc');
        if (descStatusEl) descStatusEl.textContent = steps[currentIndex].desc;
    }

    function startCountdown() {
        if (countdownInterval) clearInterval(countdownInterval);

        const timerEl = document.getElementById('countdown-timer');
        const statusEl = document.getElementById('countdown-status');

        if (!timerEl) return;

        if (remainingSeconds <= 0) {
            timerEl.textContent = formatTime(0);
            if (statusEl) statusEl.textContent = '{{ __("Arriving now!") }}';
            updateProgressRealtime();
            return;
        }

        timerEl.textContent = formatTime(remainingSeconds);
        updateProgressRealtime();

        countdownInterval = setInterval(() => {
            remainingSeconds--;
            if (remainingSeconds <= 0) {
                remainingSeconds = 0;
                timerEl.textContent = formatTime(0);
                if (statusEl) statusEl.textContent = '{{ __("Arriving now!") }}';
                clearInterval(countdownInterval);
            } else {
                timerEl.textContent = formatTime(remainingSeconds);
            }
            updateProgressRealtime();
        }, 1000);
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

    function updateTrackingUI(data) {
        // 1. Update countdown if needed
        if (data.estimated_delivery_minutes) {
            document.getElementById('countdown-container').style.display = 'flex';
            remainingSeconds = data.remaining_seconds;
            estimatedDeliveryMinutes = data.estimated_delivery_minutes;
            totalSeconds = estimatedDeliveryMinutes * 60;
            startCountdown();
        } else {
            document.getElementById('countdown-container').style.display = 'none';
            if (countdownInterval) clearInterval(countdownInterval);
        }

        // 3. Update payment status container
        const paymentProofCard = document.getElementById('payment-proof-card');
        if (paymentProofCard) {
            const paymentProofVerifiedContainer = document.getElementById('payment-verified-container');
            const paymentProofUnverifiedContainer = document.getElementById('payment-unverified-container');

            if (data.payment_verified) {
                if (paymentProofUnverifiedContainer) paymentProofUnverifiedContainer.style.display = 'none';
                if (paymentProofVerifiedContainer) paymentProofVerifiedContainer.style.display = 'flex';
            } else {
                if (paymentProofVerifiedContainer) paymentProofVerifiedContainer.style.display = 'none';
                if (paymentProofUnverifiedContainer) paymentProofUnverifiedContainer.style.display = 'flex';
            }
        }
    }

    const orderStatusUrl = '{{ route("orders.getStatus", $order->id) }}';

    function pollOrderStatus() {
        setInterval(async () => {
            try {
                const res = await fetch(orderStatusUrl);
                if (!res.ok) throw new Error('Network error');
                const data = await res.json();
                updateTrackingUI(data);
            } catch (e) {
                console.error("Failed to fetch order status:", e);
            }
        }, 5000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        if (remainingSeconds > 0) {
            startCountdown();
        }
        pollOrderStatus();
    });
</script>
@endsection
