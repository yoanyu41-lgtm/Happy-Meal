@extends('layouts.app')

@section('title', __('My Profile') . ' — Happy Meal')

@section('content')
<div style="max-width: 800px; margin: 2rem auto; padding: 0 1rem;">
    {{-- Page Title --}}
    <h1 style="font-size: 1.85rem; font-weight: 800; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
        <span class="material-icons-round" style="color: var(--primary-color);">manage_accounts</span>
        <span>{{ __('My Profile') }}</span>
    </h1>

    {{-- Tabs Controller --}}
    <div style="display: flex; gap: 0.5rem; border-bottom: 1px solid var(--border-color); margin-bottom: 2rem; padding-bottom: 0.5rem;">
        <button type="button" class="tab-btn active" onclick="switchTab('personal-info')" id="tab-personal-info" style="
            background: transparent; border: none; font-family: inherit; font-size: 0.95rem; font-weight: 700;
            padding: 0.6rem 1.25rem; cursor: pointer; border-radius: 8px; color: var(--text-primary);
            transition: all var(--transition-speed); display: flex; align-items: center; gap: 0.4rem;
        ">
            <span class="material-icons-round" style="font-size: 1.2rem;">person</span>
            <span>{{ __('Personal Information') }}</span>
        </button>
        <button type="button" class="tab-btn" onclick="switchTab('security')" id="tab-security" style="
            background: transparent; border: none; font-family: inherit; font-size: 0.95rem; font-weight: 600;
            padding: 0.6rem 1.25rem; cursor: pointer; border-radius: 8px; color: var(--text-secondary);
            transition: all var(--transition-speed); display: flex; align-items: center; gap: 0.4rem;
        ">
            <span class="material-icons-round" style="font-size: 1.2rem;">security</span>
            <span>{{ __('Security') }}</span>
        </button>
    </div>

    {{-- Error/Success Alerts inside form --}}
    @if ($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; color: #fca5a5; font-size: 0.88rem;">
            @foreach ($errors->all() as $error)
                <div style="display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.25rem;">
                    <span class="material-icons-round" style="font-size: 1.1rem; color: #ef4444;">error</span>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Tab 1: Personal Info Card --}}
    <div id="pane-personal-info" class="tab-pane" style="display: block;">
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 2rem; box-shadow: var(--card-shadow);">
            <form action="{{ route('customer.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="name" style="font-weight: 700;">{{ __('Full Name *') }}</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="email" style="font-weight: 700;">{{ __('Email Address *') }}</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="phone" style="font-weight: 700;">{{ __('Phone Number') }}</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="{{ __('e.g. 012 345 678') }}" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" for="address" style="font-weight: 700;">{{ __('Shipping Address') }}</label>

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

                    <textarea name="address" id="address" class="form-control" rows="4" placeholder="{{ __('e.g. St. 105, Boeung Keng Kang, Phnom Penh') }}">{{ old('address', $user->address) }}</textarea>
                    <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.35rem;">
                        ✏️ {{ __('You can edit the address text above if needed') }}
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span class="material-icons-round">save</span>
                    <span>{{ __('Save Changes') }}</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Tab 2: Security / Password Change Card --}}
    <div id="pane-security" class="tab-pane" style="display: none;">
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 2rem; box-shadow: var(--card-shadow);">
            <form action="{{ route('customer.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="current_password" style="font-weight: 700;">{{ __('Password *') }} ({{ __('Current Status') }})</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label" for="password" style="font-weight: 700;">{{ __('Password *') }} ({{ __('New') }})</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" for="password_confirmation" style="font-weight: 700;">{{ __('Confirm Password *') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 700; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);">
                    <span class="material-icons-round">vpn_key</span>
                    <span>{{ __('Change Password') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.04) !important;
        color: var(--text-primary) !important;
    }
    .tab-btn.active {
        background: rgba(99, 102, 241, 0.1) !important;
        color: var(--primary-color) !important;
        border: 1px solid rgba(99, 102, 241, 0.2) !important;
    }
</style>
@endsection

@section('scripts')
{{-- Leaflet.js (Free Map, No API Key) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    function switchTab(tabId) {
        // Toggle tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.style.display = 'none';
        });
        document.getElementById('pane-' + tabId).style.display = 'block';

        // Toggle active button style
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.fontWeight = '500';
            btn.style.color = 'var(--text-secondary)';
        });
        
        const activeBtn = document.getElementById('tab-' + tabId);
        activeBtn.classList.add('active');
        activeBtn.style.fontWeight = '700';
        activeBtn.style.color = 'var(--primary-color)';
    }

    // Keep active tab state on validation error
    @if($errors->has('current_password') || $errors->has('password'))
        switchTab('security');
    @endif

    // ─── Leaflet Map & GPS Logic ───────────────────────────
    let map = null;
    let marker = null;
    let mapInitialized = false;

    // Show status message
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

    // Reverse geocode
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

    // Update address field + hidden coords
    async function updateLocation(lat, lng, showLoading = true) {
        document.getElementById('delivery_lat').value = lat;
        document.getElementById('delivery_lng').value = lng;

        if (showLoading) showStatus('{{ __("Getting address...") }}', 'loading');

        const address = await reverseGeocode(lat, lng);
        document.getElementById('address').value = address;
        showStatus('{{ __("Location set successfully!") }}', 'success');
    }

    // Initialize Leaflet map
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

            marker.on('dragend', async function(e) {
                const pos = e.target.getLatLng();
                await updateLocation(pos.lat, pos.lng);
            });

            map.on('click', async function(e) {
                marker.setLatLng(e.latlng);
                await updateLocation(e.latlng.lat, e.latlng.lng);
            });

            mapInitialized = true;
        } else {
            map.setView([lat, lng], 16);
            marker.setLatLng([lat, lng]);
            setTimeout(() => map.invalidateSize(), 100);
        }
    }

    // Toggle map visibility
    function toggleMap() {
        const container = document.getElementById('map-container');
        const isHidden = container.style.display === 'none';

        if (isHidden) {
            container.style.display = 'block';
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

    // GPS Geolocation button
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
</style>
@endsection
