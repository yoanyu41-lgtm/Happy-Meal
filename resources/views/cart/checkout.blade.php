@extends('layouts.app')

@section('title', __('Checkout') . ' - Happy Meal')

@section('content')
    <h1 style="font-size: 1.85rem; font-weight: 700; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
        <span class="material-icons-round" style="color: var(--primary-color);">credit_card</span>
        <span>{{ __('Checkout') }}</span>
    </h1>

    <div class="cart-layout">
        <!-- Billing Details Form -->
        <div class="cart-card">
            <h2 style="font-size: 1.35rem; font-weight: 700; margin-bottom: 1.5rem;">{{ __('Delivery Information') }}</h2>
            
            <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="customer_name" class="form-label">{{ __('Full Name *') }}</label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="{{ __('e.g. Kok An') }}" value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" required>
                    @error('customer_name')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_email" class="form-label">{{ __('Email Address *') }}</label>
                    <input type="email" name="customer_email" id="customer_email" class="form-control" placeholder="{{ __('e.g. example@domain.com') }}" value="{{ old('customer_email', auth()->check() ? auth()->user()->email : '') }}" required>
                    @error('customer_email')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_phone" class="form-label">{{ __('Phone Number *') }}</label>
                    <input type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="{{ __('e.g. 012 345 678') }}" value="{{ old('customer_phone', auth()->check() ? auth()->user()->phone : '') }}" required>
                    @error('customer_phone')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_address" class="form-label">{{ __('Delivery Location *') }}</label>

                    {{-- GPS + Map Buttons --}}
                    <div style="display: flex; gap: 0.6rem; margin-bottom: 0.6rem;">
                        <button type="button" id="btn-gps" onclick="getGPSLocation()" style="
                            display: flex; align-items: center; gap: 0.4rem;
                            background: linear-gradient(135deg, #10b981, #059669);
                            color: #fff; border: none; border-radius: 10px;
                            padding: 0.55rem 1rem; font-size: 0.85rem; font-weight: 700;
                            cursor: pointer; font-family: inherit;
                            box-shadow: 0 4px 12px rgba(16,185,129,0.35);
                            transition: opacity 0.2s;
                        ">
                            <span class="material-icons-round" style="font-size:1.1rem;">my_location</span>
                            {{ __('Use My Location') }}
                        </button>
                        <button type="button" id="btn-toggle-map" onclick="toggleMap()" style="
                            display: flex; align-items: center; gap: 0.4rem;
                            background: rgba(99,102,241,0.12); color: var(--primary-color);
                            border: 1px solid rgba(99,102,241,0.3); border-radius: 10px;
                            padding: 0.55rem 1rem; font-size: 0.85rem; font-weight: 700;
                            cursor: pointer; font-family: inherit; transition: all 0.2s;
                        ">
                            <span class="material-icons-round" style="font-size:1.1rem;">map</span>
                            {{ __('Pick on Map') }}
                        </button>
                    </div>

                    {{-- Status Message --}}
                    <div id="location-status" style="
                        display: none; font-size: 0.82rem; padding: 0.5rem 0.8rem;
                        border-radius: 8px; margin-bottom: 0.6rem;
                        align-items: center; gap: 0.4rem;
                    "></div>

                    {{-- Map Container --}}
                    <div id="map-container" style="display: none; margin-bottom: 0.75rem; border-radius: 14px; overflow: hidden; border: 1.5px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.25);">
                        <div id="location-map" style="height: 280px; width: 100%;"></div>
                        <div style="background: rgba(0,0,0,0.4); padding: 0.5rem 0.75rem; font-size: 0.78rem; color: rgba(255,255,255,0.7); text-align: center;">
                            📌 {{ __('Drag the pin to your exact delivery location') }}
                        </div>
                    </div>

                    {{-- Coordinates (hidden) --}}
                    <input type="hidden" id="delivery_lat" name="delivery_lat">
                    <input type="hidden" id="delivery_lng" name="delivery_lng">

                    {{-- Address Textarea --}}
                    <textarea name="customer_address" id="customer_address" class="form-control" rows="3"
                        placeholder="{{ __('e.g. St. 105, Boeung Keng Kang, Phnom Penh') }}"
                        required>{{ old('customer_address', auth()->check() ? auth()->user()->address : '') }}</textarea>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.35rem;">
                        ✏️ {{ __('You can edit the address text above if needed') }}
                    </div>

                    @error('customer_address')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 1rem;">
                    <span class="material-icons-round">shopping_bag</span>
                    <span>{{ __('Confirm Order') }}</span>
                </button>
            </form>
        </div>

        <!-- Order Summary side column -->
        <div class="cart-summary">
            <h2 class="summary-title">{{ __('Ordered Dishes') }}</h2>
            
            <div style="max-height: 250px; overflow-y: auto; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 1rem; padding-right: 0.5rem;">
                @foreach($cart as $key => $details)
                    @php
                        $product = $products[$details['product_id']] ?? null;
                        $addonPrice = $details['addon_price'] ?? 0;
                        $itemPrice = ($product ? $product->price : 0) + $addonPrice;
                        $quantity = $details['quantity'];
                    @endphp
                    @if($product)
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color);">
                            <div style="flex-grow: 1; min-width: 0;">
                                <div style="font-weight: 600; font-size: 0.9rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $product->name }}</div>
                                
                                @if(!empty($details['options']))
                                    <div style="font-size: 0.75rem; color: var(--primary-color); display: flex; flex-direction: column; gap: 0.05rem; margin: 0.15rem 0;">
                                        @if(isset($details['options']['spice']))
                                            <span>{{ __('Spice Level') }}: {{ __($details['options']['spice']) }}</span>
                                        @endif
                                        @if(isset($details['options']['sweetness']))
                                            <span>{{ __('Sweetness Level') }}: {{ $details['options']['sweetness'] }}</span>
                                        @endif
                                        @if(isset($details['options']['ice']))
                                            <span>{{ __('Ice Level') }}: {{ $details['options']['ice'] }}</span>
                                        @endif
                                        @if(isset($details['options']['addons']) && count($details['options']['addons']) > 0)
                                            <span>{{ __('Add-ons') }}: 
                                                @foreach($details['options']['addons'] as $addon)
                                                    {{ $product ? $product->getAddonLabel($addon) : __($addon) }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div style="color: var(--text-secondary); font-size: 0.8rem;">${{ number_format($itemPrice, 2) }} x {{ $quantity }} {{ $quantity > 1 ? __('dishes') : __('dish') }}</div>
                            </div>
                            <div style="font-weight: 700; font-size: 0.9rem;">
                                ${{ number_format($itemPrice * $quantity, 2) }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="summary-row" style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                <span style="color: var(--text-secondary);">{{ __('Total') }}</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>
            
            <div class="summary-row">
                <span style="color: var(--text-secondary);">{{ __('Shipping') }}</span>
                <span style="color: var(--success-color);">{{ __('Free Delivery') }}</span>
            </div>

            <div class="summary-row summary-total">
                <span>{{ __('Total Amount to Pay') }}</span>
                <span style="color: var(--primary-color); font-size: 1.5rem;">${{ number_format($total, 2) }}</span>
            </div>
            
            <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem; align-items: center; justify-content: center; font-size: 0.85rem; color: var(--text-secondary);">
                <span class="material-icons-round" style="color: var(--success-color); font-size: 1.15rem;">shield</span>
                <span>{{ __('Secure Payment System') }}</span>
            </div>

            {{-- ════ KHQR PAYMENT SECTION ════ --}}
            <div style="
                margin-top: 1.5rem;
                background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(16,185,129,0.05));
                border: 1.5px solid rgba(99,102,241,0.25);
                border-radius: 16px;
                padding: 1.25rem 1rem;
                text-align: center;
            ">
                {{-- Header --}}
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                    <span class="material-icons-round" style="color: #10b981; font-size: 1.2rem;">qr_code_2</span>
                    <span style="font-weight: 800; font-size: 0.95rem; color: var(--text-primary);">{{ __('Pay via KHQR') }}</span>
                    <span style="background: #10b981; color: #fff; font-size: 0.65rem; font-weight: 700; border-radius: 99px; padding: 0.1rem 0.5rem;">LIVE</span>
                </div>

                {{-- QR Code --}}
                <div style="
                    background: #fff;
                    border-radius: 12px;
                    padding: 0.75rem;
                    display: inline-block;
                    margin-bottom: 0.75rem;
                    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
                ">
                    {{-- Using a QR code generator API with dynamic valid EMVCo KHQR payload --}}
                    @php
                        $khqrPayload = \App\Helpers\KHQRHelper::generateKHQR(
                            'wing_khqr@wing',
                            '100764918',
                            'Wing Bank',
                            'YU YOAN',
                            'Phnom Penh',
                            $total
                        );
                    @endphp
                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($khqrPayload) }}"
                        alt="KHQR Payment QR Code"
                        style="width: 140px; height: 140px; display: block;"
                    >
                </div>

                {{-- Account Info --}}
                <div style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.8;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.15rem;">
                        <span>{{ __('Bank') }}:</span>
                        <strong style="color:var(--text-primary);">Wing Bank</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.15rem;">
                        <span>{{ __('Account Name') }}:</span>
                        <strong style="color:var(--text-primary);">YU YOAN</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <span>{{ __('Account Number') }}:</span>
                        <strong style="color:var(--primary-color);">100 764 918</strong>
                    </div>
                </div>

                {{-- Amount --}}
                <div style="
                    margin-top: 0.75rem;
                    background: rgba(99,102,241,0.1);
                    border-radius: 8px;
                    padding: 0.5rem;
                    font-size: 0.85rem;
                ">
                    {{ __('Amount to transfer') }}:
                    <strong style="color: var(--primary-color); font-size: 1rem;">${{ number_format($total, 2) }}</strong>
                </div>

                {{-- Note --}}
                <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.75rem; line-height: 1.5;">
                    📌 {{ __('Scan QR, transfer exact amount, then click Confirm Order below.') }}
                </p>


            </div>
        </div>
    </div>
@endsection

@section('scripts')
{{-- Leaflet.js (Free Map, No API Key) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map = null;
    let marker = null;
    let mapInitialized = false;

    // ─── Show status message ───────────────────────────────
    function showStatus(msg, type) {
        const el = document.getElementById('location-status');
        el.style.display = 'flex';
        if (type === 'loading') {
            el.style.background = 'rgba(99,102,241,0.1)';
            el.style.border = '1px solid rgba(99,102,241,0.3)';
            el.style.color = 'var(--primary-color)';
            el.innerHTML = `<span class="material-icons-round" style="font-size:1rem; animation: spin 1s linear infinite;">autorenew</span> ${msg}`;
        } else if (type === 'success') {
            el.style.background = 'rgba(16,185,129,0.1)';
            el.style.border = '1px solid rgba(16,185,129,0.3)';
            el.style.color = '#10b981';
            el.innerHTML = `<span class="material-icons-round" style="font-size:1rem;">check_circle</span> ${msg}`;
        } else {
            el.style.background = 'rgba(239,68,68,0.1)';
            el.style.border = '1px solid rgba(239,68,68,0.3)';
            el.style.color = '#ef4444';
            el.innerHTML = `<span class="material-icons-round" style="font-size:1rem;">error</span> ${msg}`;
        }
    }

    // ─── Reverse geocode (lat,lng) → address text ─────────
    async function reverseGeocode(lat, lng) {
        try {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=km,en`;
            const res = await fetch(url, {
                headers: { 'Accept-Language': 'km,en' }
            });
            const data = await res.json();
            if (data && data.display_name) {
                return data.display_name;
            }
        } catch (e) {}
        return `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }

    // ─── Update address field + hidden coords ─────────────
    async function updateLocation(lat, lng, showLoading = true) {
        document.getElementById('delivery_lat').value = lat;
        document.getElementById('delivery_lng').value = lng;

        if (showLoading) showStatus('{{ __("Getting address...") }}', 'loading');

        const address = await reverseGeocode(lat, lng);
        document.getElementById('customer_address').value = address;
        showStatus('{{ __("Location set successfully!") }}', 'success');
    }

    // ─── Initialise Leaflet map ────────────────────────────
    function initMap(lat, lng) {
        if (!mapInitialized) {
            map = L.map('location-map', { zoomControl: true }).setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                maxZoom: 19,
            }).addTo(map);

            // Custom red icon
            const icon = L.divIcon({
                html: `<div style="
                    width: 36px; height: 36px;
                    background: linear-gradient(135deg, #ef4444, #dc2626);
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    border: 3px solid #fff;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
                "></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 36],
                className: '',
            });

            marker = L.marker([lat, lng], { draggable: true, icon }).addTo(map);

            // Update address when dragged
            marker.on('dragend', async function(e) {
                const pos = e.target.getLatLng();
                await updateLocation(pos.lat, pos.lng);
            });

            // Click on map to move pin
            map.on('click', async function(e) {
                marker.setLatLng(e.latlng);
                await updateLocation(e.latlng.lat, e.latlng.lng);
            });

            mapInitialized = true;
        } else {
            // Already initialized — just move marker and center
            map.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);
            setTimeout(() => map.invalidateSize(), 100);
        }
    }

    // ─── Toggle map visibility ─────────────────────────────
    function toggleMap() {
        const container = document.getElementById('map-container');
        const isHidden = container.style.display === 'none';

        if (isHidden) {
            container.style.display = 'block';
            // Default: Phnom Penh center if no GPS yet
            const lat = parseFloat(document.getElementById('delivery_lat').value) || 11.5564;
            const lng = parseFloat(document.getElementById('delivery_lng').value) || 104.9282;
            setTimeout(() => {
                initMap(lat, lng);
                if (map) map.invalidateSize();
            }, 100);
            document.getElementById('btn-toggle-map').innerHTML = `<span class="material-icons-round" style="font-size:1.1rem;">map</span> {{ __('Hide Map') }}`;
        } else {
            container.style.display = 'none';
            document.getElementById('btn-toggle-map').innerHTML = `<span class="material-icons-round" style="font-size:1.1rem;">map</span> {{ __('Pick on Map') }}`;
        }
    }

    // ─── GPS Button ────────────────────────────────────────
    function getGPSLocation() {
        if (!navigator.geolocation) {
            showStatus('{{ __("Your browser does not support location.") }}', 'error');
            return;
        }

        const btn = document.getElementById('btn-gps');
        btn.style.opacity = '0.6';
        btn.disabled = true;
        showStatus('{{ __("Getting your location...") }}', 'loading');

        navigator.geolocation.getCurrentPosition(
            async function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                // Show map
                document.getElementById('map-container').style.display = 'block';
                document.getElementById('btn-toggle-map').innerHTML = `<span class="material-icons-round" style="font-size:1.1rem;">map</span> {{ __('Hide Map') }}`;

                setTimeout(async () => {
                    initMap(lat, lng);
                    if (map) map.invalidateSize();
                    await updateLocation(lat, lng);
                    btn.style.opacity = '1';
                    btn.disabled = false;
                }, 200);
            },
            function(err) {
                let msg = '{{ __("Location access denied.") }}';
                if (err.code === 1) msg = '{{ __("Please allow location access in your browser.") }}';
                if (err.code === 2) msg = '{{ __("Location unavailable. Try picking on map.") }}';
                showStatus(msg, 'error');
                btn.style.opacity = '1';
                btn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }
</script>

<style>
    @keyframes spin { to { transform: rotate(360deg); } }
    .leaflet-container { font-family: inherit; }
    #btn-gps:hover { opacity: 0.85 !important; }
    #btn-toggle-map:hover { background: rgba(99,102,241,0.2) !important; }
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
</script>
@endsection
