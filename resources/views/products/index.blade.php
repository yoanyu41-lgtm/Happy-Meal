@extends('layouts.app')

@section('title', __('Happy Meal - Delicious Online Food Ordering'))

@section('content')
    @php
        $featuredProduct = $products->firstWhere('category', 'alacarte') ?? $products->first();
    @endphp

    <!-- Hero Header -->
    <header class="hero hero-split">
        <div class="hero-content">
            <h1>{{ __('Order delicious warm food with') }} <span>Happy Meal</span></h1>
            <p>{{ __('We offer traditional Khmer dishes, freshly cooked, clean, and delivered fast to your place!') }}</p>
            <a href="#catalog" class="btn btn-primary" style="margin-top: 0.5rem; padding: 0.85rem 1.75rem;">
                <span>{{ __('Order Now') }}</span>
                <span class="material-icons-round">arrow_forward</span>
            </a>
        </div>

        @if($featuredProduct)
            <div class="hero-featured-showcase">
                <div class="featured-glow-ring"></div>
                <div class="featured-dish-card">
                    <!-- Decorative Badge -->
                    <div class="featured-badge">
                        <span class="material-icons-round">star</span>
                        <span>{{ __('Chef\'s Choice') }}</span>
                    </div>
                    
                    <!-- Dish Image Wrapper -->
                    <div class="featured-dish-img-container">
                        <img src="{{ asset('images/featured_fish_amok.png') }}" alt="{{ $featuredProduct->name }}" class="featured-dish-img">
                    </div>
                    
                    <!-- Dish Info -->
                    <div class="featured-dish-info">
                        <h3>{{ $featuredProduct->name }}</h3>
                        <p>{{ Str::limit($featuredProduct->description, 115) }}</p>
                        
                        <div class="featured-dish-price-row">
                            <span class="featured-dish-price">${{ number_format($featuredProduct->price, 2) }}</span>
                            <a href="{{ route('products.show', $featuredProduct->id) }}" class="btn btn-primary btn-sm" style="padding: 0.55rem 1.15rem;">
                                <span>{{ __('Order Dish') }}</span>
                                <span class="material-icons-round">shopping_cart</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </header>

    <!-- Catalog Section -->
    <section id="catalog" style="scroll-margin-top: 100px;">
        <h2 style="font-size: 1.85rem; font-weight: 700; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-icons-round" style="color: var(--primary-color);">restaurant_menu</span>
            <span>{{ __('Featured Dishes') }}</span>
        </h2>

        <!-- Search Box -->
        <div class="search-container">
            <span class="material-icons-round search-icon">search</span>
            <input type="text" id="search-input" class="search-input" placeholder="{{ __('Search for dishes...') }}" aria-label="{{ __('Search') }}" autocomplete="off">
        </div>

        <!-- Category Filter Tabs -->
        <div class="categories-filter-container">
            <button class="category-tab-btn active" data-category="all">
                <span class="material-icons-round">all_inclusive</span>
                <span>{{ __('All') }}</span>
            </button>
            <button class="category-tab-btn" data-category="breakfast">
                <span class="material-icons-round">breakfast_dining</span>
                <span>{{ __('Breakfast') }}</span>
            </button>
            <button class="category-tab-btn" data-category="alacarte">
                <span class="material-icons-round">restaurant</span>
                <span>{{ __('A La Carte') }}</span>
            </button>
            <button class="category-tab-btn" data-category="night">
                <span class="material-icons-round">nightlife</span>
                <span>{{ __('Night Menu') }}</span>
            </button>
            <button class="category-tab-btn" data-category="drinks">
                <span class="material-icons-round">local_bar</span>
                <span>{{ __('Drinks') }}</span>
            </button>
        </div>
        
        <div class="products-grid">
            @forelse($products as $product)
                <div class="product-card" data-category="{{ $product->category }}">
                    <div class="product-image-wrapper">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                        @else
                            <div style="position: absolute; top:0; left:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#1e293b;">
                                <span class="material-icons-round" style="font-size: 3rem; color: var(--text-secondary);">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="product-info">
                        <a href="{{ route('products.show', $product->id) }}" class="product-name">{{ $product->name }}</a>
                        <p class="product-desc">{{ $product->description }}</p>
                        
                        <div class="product-price-row">
                            <span class="product-price">${{ number_format($product->price, 2) }}</span>
                            
                            @if($product->stock > 0)
                                <button type="button" class="btn btn-primary btn-sm btn-add-to-cart-trigger" 
                                    data-id="{{ $product->id }}" 
                                    data-name="{{ $product->name }}" 
                                    data-price="{{ $product->price }}" 
                                    data-category="{{ $product->category }}" 
                                    data-stock="{{ $product->stock }}"
                                    data-image="{{ $product->image ?? '' }}"
                                    style="padding: 0.5rem; border-radius: 50%; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;" aria-label="{{ __('Add to Cart') }}">
                                    <span class="material-icons-round" style="font-size: 1.25rem;">add_shopping_cart</span>
                                </button>
                            @else
                                <span class="stock-badge stock-out">{{ __('Out of Stock') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem; background: var(--bg-card); border-radius: 12px; border: 1px solid var(--border-color);">
                    <span class="material-icons-round" style="font-size: 4rem; color: var(--text-secondary); margin-bottom: 1rem;">inventory</span>
                    <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">{{ __('No products found') }}</h3>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Hidden form for direct add-to-cart -->
    <form id="direct-add-to-cart-form" action="{{ route('cart.add') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="product_id" id="direct-product-id">
        <input type="hidden" name="quantity" value="1">
    </form>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.category-tab-btn');
            const productCards = document.querySelectorAll('.product-card');
            const searchInput = document.getElementById('search-input');

            let activeCategory = 'all';
            let searchQuery = '';

            function filterProducts() {
                productCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    const nameEl = card.querySelector('.product-name');
                    const descEl = card.querySelector('.product-desc');
                    
                    const name = nameEl ? nameEl.textContent.toLowerCase() : '';
                    const desc = descEl ? descEl.textContent.toLowerCase() : '';
                    
                    const matchesCategory = (activeCategory === 'all' || category === activeCategory);
                    const matchesSearch = name.includes(searchQuery) || desc.includes(searchQuery);

                    if (matchesCategory && matchesSearch) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            }

            // Tab filtering
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    activeCategory = this.getAttribute('data-category');
                    filterProducts();
                });
            });

            // Search filtering
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    searchQuery = this.value.toLowerCase().trim();
                    filterProducts();
                });
            }

            // Run initial filter to set up state
            filterProducts();

            // Event delegation on grid for Add to Cart click
            const grid = document.querySelector('.products-grid');
            if (grid) {
                grid.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-add-to-cart-trigger');
                    if (btn) {
                        e.preventDefault();
                        const id = btn.dataset.id;
                        document.getElementById('direct-product-id').value = id;
                        document.getElementById('direct-add-to-cart-form').submit();
                    }
                });
            }
        });
    </script>
@endsection
