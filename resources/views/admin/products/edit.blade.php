@extends('layouts.app')

@section('title', __('Edit Dish') . ' - Happy Meal Admin')

@section('content')
    <!-- Back to Admin Dashboard -->
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 0.25rem;">
        <span class="material-icons-round" style="font-size: 1.15rem;">arrow_back</span>
        <span>{{ __('Return to Dashboard') }}</span>
    </a>

    <div style="max-width: 700px; margin: 0 auto;">
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 16px; padding: 2rem; box-shadow: var(--card-shadow);">
            <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                <span class="material-icons-round" style="color: var(--primary-color);">restaurant</span>
                <span>{{ __('Edit Dish') }}</span>
            </h1>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Dish Name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('e.g. Beef Lok Lak') }}" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category" class="form-label">{{ __('Category') }} *</label>
                    <select name="category" id="category" class="form-control" required style="font-family: var(--font-primary);">
                        <option value="breakfast" {{ old('category', $product->category) === 'breakfast' ? 'selected' : '' }}>{{ __('Breakfast') }}</option>
                        <option value="alacarte" {{ old('category', $product->category) === 'alacarte' ? 'selected' : '' }}>{{ __('A La Carte') }}</option>
                        <option value="night" {{ old('category', $product->category) === 'night' ? 'selected' : '' }}>{{ __('Night Menu') }}</option>
                        <option value="drinks" {{ old('category', $product->category) === 'drinks' ? 'selected' : '' }}>{{ __('Drinks') }}</option>
                    </select>
                    @error('category')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">{{ __('Price') }} (USD) *</label>
                    <input type="number" name="price" id="price" class="form-control" placeholder="e.g. 5.00" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                    @error('price')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock" class="form-label">{{ __('Available') }} ({{ __('dishes') }}) *</label>
                    <input type="number" name="stock" id="stock" class="form-control" placeholder="e.g. 20" min="0" value="{{ old('stock', $product->stock) }}" required>
                    @error('stock')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="prep_time_minutes" class="form-label">{{ __('Preparation Time (minutes)') }} *</label>
                    <input type="number" name="prep_time_minutes" id="prep_time_minutes" class="form-control" placeholder="e.g. 15" min="0" value="{{ old('prep_time_minutes', $product->prep_time_minutes) }}" required>
                    @error('prep_time_minutes')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image" class="form-label">{{ __('Dish Image') }}</label>
                    @if($product->image)
                        <div style="margin-bottom: 1rem;">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                            <span style="display:block; font-size:0.8rem; color:var(--text-secondary);">{{ __('Current Image') }}</span>
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    <small style="color: var(--text-secondary); display:block; margin-top:0.25rem;">{{ __('Select new image to replace...') }}</small>
                    @error('image')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px dashed var(--border-color); margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.35rem;">
                        <span class="material-icons-round" style="color: var(--primary-color);">tune</span>
                        <span>{{ __('Customization Options') }}</span>
                    </h3>

                    <!-- Food Customizations -->
                    <div id="food-customizations-section" style="display: flex; flex-direction: column; gap: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="has_spice_level" value="1" {{ old('has_spice_level', $product->has_spice_level) ? 'checked' : '' }} style="accent-color: var(--primary-color);">
                            <span style="font-weight: 600;">{{ __('Enable Spice Level') }} (Not Spicy, Medium Spicy, Extra Spicy)</span>
                        </label>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">{{ __('Add-on options') }}:</span>
                            
                            <!-- Egg Addon -->
                            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; min-width: 40px; margin-bottom: 0;">
                                    <input type="checkbox" name="addon_egg_enabled" value="1" {{ old('addon_egg_enabled', $product->isAddonEnabled('egg')) ? 'checked' : '' }} style="accent-color: var(--primary-color);">
                                </label>
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-grow: 1;">
                                    <input type="text" name="addon_egg_name" class="form-control" style="padding: 0.35rem 0.6rem; font-size: 0.9rem; max-width: 220px;" value="{{ old('addon_egg_name', $product->addons_config['egg']['name'] ?? __('Extra Egg')) }}" placeholder="{{ __('Extra Egg') }}">
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="color: var(--text-secondary);">Price ($):</span>
                                    <input type="number" name="addon_egg_price" step="0.01" min="0" class="form-control" style="width: 80px; padding: 0.35rem 0.6rem; font-size: 0.9rem;" value="{{ old('addon_egg_price', $product->getAddonPrice('egg', 0.50)) }}">
                                </div>
                            </div>

                            <!-- Meat Addon -->
                            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; min-width: 40px; margin-bottom: 0;">
                                    <input type="checkbox" name="addon_meat_enabled" value="1" {{ old('addon_meat_enabled', $product->isAddonEnabled('meat')) ? 'checked' : '' }} style="accent-color: var(--primary-color);">
                                </label>
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-grow: 1;">
                                    <input type="text" name="addon_meat_name" class="form-control" style="padding: 0.35rem 0.6rem; font-size: 0.9rem; max-width: 220px;" value="{{ old('addon_meat_name', $product->addons_config['meat']['name'] ?? __('Extra Meat')) }}" placeholder="{{ __('Extra Meat') }}">
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="color: var(--text-secondary);">Price ($):</span>
                                    <input type="number" name="addon_meat_price" step="0.01" min="0" class="form-control" style="width: 80px; padding: 0.35rem 0.6rem; font-size: 0.9rem;" value="{{ old('addon_meat_price', $product->getAddonPrice('meat', 1.50)) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Drinks Customizations -->
                    <div id="drink-customizations-section" style="display: none; flex-direction: column; gap: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="has_sweetness_level" value="1" {{ old('has_sweetness_level', $product->has_sweetness_level) ? 'checked' : '' }} style="accent-color: var(--primary-color);">
                            <span style="font-weight: 600;">{{ __('Enable Sweetness Level') }} (100%, 50%, 0%)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="has_ice_level" value="1" {{ old('has_ice_level', $product->has_ice_level) ? 'checked' : '' }} style="accent-color: var(--primary-color);">
                            <span style="font-weight: 600;">{{ __('Enable Ice Level') }} (100%, 50%, 0%)</span>
                        </label>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="font-weight: 700; font-size: 0.9rem; color: var(--text-primary);">{{ __('Add-on Toppings') }}:</span>

                            <!-- Jelly Addon -->
                            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; min-width: 40px; margin-bottom: 0;">
                                    <input type="checkbox" name="addon_jelly_enabled" value="1" {{ old('addon_jelly_enabled', $product->isAddonEnabled('jelly')) ? 'checked' : '' }} style="accent-color: var(--primary-color);">
                                </label>
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-grow: 1;">
                                    <input type="text" name="addon_jelly_name" class="form-control" style="padding: 0.35rem 0.6rem; font-size: 0.9rem; max-width: 220px;" value="{{ old('addon_jelly_name', $product->addons_config['jelly']['name'] ?? __('Extra Jelly')) }}" placeholder="{{ __('Extra Jelly') }}">
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="color: var(--text-secondary);">Price ($):</span>
                                    <input type="number" name="addon_jelly_price" step="0.01" min="0" class="form-control" style="width: 80px; padding: 0.35rem 0.6rem; font-size: 0.9rem;" value="{{ old('addon_jelly_price', $product->getAddonPrice('jelly', 0.50)) }}">
                                </div>
                            </div>

                            <!-- Coconut Jelly Addon -->
                            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; min-width: 40px; margin-bottom: 0;">
                                    <input type="checkbox" name="addon_coconut_enabled" value="1" {{ old('addon_coconut_enabled', $product->isAddonEnabled('coconut')) ? 'checked' : '' }} style="accent-color: var(--primary-color);">
                                </label>
                                <div style="display: flex; align-items: center; gap: 0.5rem; flex-grow: 1;">
                                    <input type="text" name="addon_coconut_name" class="form-control" style="padding: 0.35rem 0.6rem; font-size: 0.9rem; max-width: 220px;" value="{{ old('addon_coconut_name', $product->addons_config['coconut']['name'] ?? __('Extra Coconut Jelly')) }}" placeholder="{{ __('Extra Coconut Jelly') }}">
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="color: var(--text-secondary);">Price ($):</span>
                                    <input type="number" name="addon_coconut_price" step="0.01" min="0" class="form-control" style="width: 80px; padding: 0.35rem 0.6rem; font-size: 0.9rem;" value="{{ old('addon_coconut_price', $product->getAddonPrice('coconut', 0.50)) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Custom Add-ons Section -->
                    <div style="margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px dashed var(--border-color); margin-bottom: 1.5rem;">
                        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem;">
                            <span class="material-icons-round" style="color: var(--primary-color);">add_circle</span>
                            <span>{{ __('Custom Add-on Options') }}</span>
                        </h3>
                        <small style="color: var(--text-secondary); display:block; margin-bottom: 1rem;">
                            {{ __('Write custom options like Extra Veggies, Extra Rice') }}
                        </small>
                        
                        <div id="custom-addons-container" style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1rem;">
                            <!-- Dynamically added rows will go here -->
                        </div>

                        <button type="button" onclick="addCustomAddonRow()" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.8rem; padding: 0.4rem 0.75rem; border-color: rgba(99,102,241,0.3); color: var(--primary-color); background: transparent; cursor: pointer;">
                            <span class="material-icons-round" style="font-size: 1.1rem; color: var(--primary-color);">add</span>
                            <span>{{ __('Add Custom Option') }}</span>
                        </button>
                    </div>

                    <div class="form-group">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" id="description" class="form-control" rows="5" placeholder="{{ __('Enter dish description...') }}">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span style="color: var(--danger-color); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; margin-top: 1rem;">
                    <span class="material-icons-round">save</span>
                    <span>{{ __('Update Dish') }}</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        let customAddonIndex = 0;
        function addCustomAddonRow(name = '', price = 0.50) {
            const container = document.getElementById('custom-addons-container');
            const row = document.createElement('div');
            row.style.display = 'flex';
            row.style.alignItems = 'center';
            row.style.gap = '0.75rem';
            row.style.flexWrap = 'wrap';
            row.id = `custom-addon-row-${customAddonIndex}`;
            
            row.innerHTML = `
                <input type="text" name="custom_addons[${customAddonIndex}][name]" class="form-control" style="flex-grow:1; min-width: 180px; padding: 0.45rem 0.75rem; font-size: 0.9rem;" placeholder="e.g. Extra Rice / ថែមបាយ" value="${name}" required>
                <div style="display: flex; align-items: center; gap: 0.35rem;">
                    <span style="color: var(--text-secondary); font-size:0.85rem;">Price ($):</span>
                    <input type="number" name="custom_addons[${customAddonIndex}][price]" step="0.01" min="0" class="form-control" style="width: 100px; padding: 0.45rem 0.75rem; font-size: 0.9rem;" value="${parseFloat(price).toFixed(2)}" required>
                </div>
                <button type="button" onclick="removeCustomAddonRow(${customAddonIndex})" class="btn btn-secondary btn-sm" style="padding: 0.35rem; color: var(--danger-color); border-color: rgba(239,68,68,0.25); width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; border-radius:50%; background: transparent; cursor: pointer;" title="Delete">
                    <span class="material-icons-round" style="font-size: 1.15rem;">delete</span>
                </button>
            `;
            container.appendChild(row);
            customAddonIndex++;
        }

        function removeCustomAddonRow(index) {
            const row = document.getElementById(`custom-addon-row-${index}`);
            if (row) row.remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const foodSect = document.getElementById('food-customizations-section');
            const drinkSect = document.getElementById('drink-customizations-section');

            function toggleCustomizations() {
                if (categorySelect.value === 'drinks') {
                    foodSect.style.display = 'none';
                    drinkSect.style.display = 'flex';
                } else {
                    foodSect.style.display = 'flex';
                    drinkSect.style.display = 'none';
                }
            }

            categorySelect.addEventListener('change', toggleCustomizations);
            toggleCustomizations();

            // Pre-populate custom addons
            @if(!empty($product->addons_config['custom']))
                @foreach($product->addons_config['custom'] as $addon)
                    addCustomAddonRow("{{ $addon['name'] }}", {{ $addon['price'] }});
                @endforeach
            @endif
        });
    </script>
@endsection
