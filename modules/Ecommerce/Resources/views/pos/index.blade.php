@extends('layouts.admin')

@section('title', 'Point of Sale')

@section('content')
<!-- POS Specific Styles to hide the standard admin sidebar and header -->
<style>
    aside.main-sidebar, header.main-header { display: none !important; }
    .content-wrapper { margin-left: 0 !important; margin-top: 0 !important; padding-top: 0 !important; height: 100vh; overflow: hidden; background-color: #f8f9fa; }
    .pos-container { height: 100vh; display: flex; flex-direction: column; }
    .pos-header { background: #fff; padding: 15px 25px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .pos-main { flex: 1; display: flex; overflow: hidden; }
    .pos-products { flex: 1; padding: 20px; overflow-y: auto; }
    .pos-cart { width: 400px; background: #fff; border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; box-shadow: -2px 0 10px rgba(0,0,0,0.02); }
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 20px; }
    .product-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; cursor: pointer; transition: all 0.2s ease; }
    .product-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: #7c3aed; }
    .product-img-wrap { height: 140px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
    .product-details { padding: 15px; }
    .cart-header { padding: 20px; border-bottom: 1px solid #e2e8f0; }
    .cart-items { flex: 1; overflow-y: auto; padding: 20px; }
    .cart-summary { padding: 20px; background: #f8f9fa; border-top: 1px solid #e2e8f0; }
    .cart-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #e2e8f0; }
    .checkout-btn { width: 100%; padding: 15px; font-size: 1.1rem; font-weight: bold; border-radius: 10px; }
    .numpad-btn { width: 100%; height: 60px; font-size: 1.25rem; font-weight: bold; border-radius: 10px; background: #fff; border: 1px solid #e2e8f0; color: #4a5568; }
    .numpad-btn:hover { background: #f1f5f9; }
</style>

<div class="pos-container">
    <!-- Header -->
    <div class="pos-header">
        <div class="d-flex align-items-center">
            <a href="{{ route('ecommerce.dashboard') }}" class="btn btn-sm btn-light mr-3 text-muted rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="material-icons-outlined">arrow_back</i>
            </a>
            <h2 class="h4 mb-0 font-weight-bold text-gray-800 d-flex align-items-center">
                <i class="material-icons-outlined text-purple mr-2">point_of_sale</i> Retail POS Mode
            </h2>
        </div>
        <div class="d-flex align-items-center">
            <div class="input-group input-group-sm mr-3" style="width: 300px;">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0 rounded-left-pill pl-3"><i class="material-icons-outlined text-sm">qr_code_scanner</i></span>
                </div>
                <input type="text" class="form-control bg-light border-left-0 rounded-right-pill pr-3" placeholder="Scan barcode or search...">
            </div>
            <button class="btn btn-sm btn-outline-secondary mr-2 rounded-pill px-3"><i class="material-icons-outlined align-middle mr-1 text-sm">pause_circle</i> Hold Order</button>
            <div class="dropdown">
                <button class="btn btn-sm btn-light rounded-circle" type="button" data-toggle="dropdown" style="width: 40px; height: 40px;">
                    <i class="material-icons-outlined">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                    <a class="dropdown-item" href="#"><i class="material-icons-outlined mr-2 align-middle text-sm">settings</i> Settings</a>
                    <a class="dropdown-item" href="#"><i class="material-icons-outlined mr-2 align-middle text-sm">sync</i> Sync Inventory</a>
                </div>
            </div>
        </div>
    </div>

    <div class="pos-main">
        <!-- Products Grid -->
        <div class="pos-products">
            <!-- Category Tabs -->
            <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active rounded-pill px-4 font-weight-bold" id="pills-all-tab" data-toggle="pill" href="#pills-all">All Products</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link rounded-pill px-4 text-muted bg-white border" id="pills-electronics-tab" data-toggle="pill" href="#pills-electronics">Electronics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill px-4 text-muted bg-white border" id="pills-apparel-tab" data-toggle="pill" href="#pills-apparel">Apparel</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-all" role="tabpanel">
                    <div class="product-grid">
                        @forelse($products as $product)
                            <div class="product-card" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})">
                                <div class="product-img-wrap">
                                    <i class="material-icons-outlined text-4xl text-muted opacity-25">inventory_2</i>
                                </div>
                                <div class="product-details">
                                    <h6 class="font-weight-bold text-gray-800 mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="font-weight-bold text-purple">@money($product->price, setting('default.currency'), true)</span>
                                        <span class="text-xs text-muted">{{ $product->stock_quantity }} in stock</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <i class="material-icons-outlined text-5xl text-muted opacity-25 mb-3">inbox</i>
                                <h5>No active products available</h5>
                                <p class="text-muted">Please add products to your catalog to use the POS.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Cart -->
        <div class="pos-cart">
            <div class="cart-header">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 font-weight-bold text-gray-800">Current Order</h5>
                    <button class="btn btn-sm btn-link text-danger p-0" onclick="clearCart()"><i class="material-icons-outlined align-middle text-sm">delete_outline</i> Clear</button>
                </div>
                <button class="btn btn-outline-primary btn-block rounded-pill text-left d-flex align-items-center justify-content-between px-3">
                    <span class="d-flex align-items-center"><i class="material-icons-outlined mr-2">person_add</i> Select Customer</span>
                    <i class="material-icons-outlined text-sm">chevron_right</i>
                </button>
            </div>
            
            <div class="cart-items" id="cartItemsContainer">
                <div class="text-center py-5 text-muted h-100 d-flex flex-column justify-content-center align-items-center" id="emptyCartMessage">
                    <i class="material-icons-outlined text-5xl mb-2 opacity-25">shopping_basket</i>
                    <p>Cart is empty</p>
                </div>
                <!-- Dynamic Cart Items injected here -->
            </div>

            <div class="cart-summary">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="font-weight-bold text-gray-800" id="cartSubtotal">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax (0%)</span>
                    <span class="font-weight-bold text-gray-800">$0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom border-dashed">
                    <span class="text-muted">Discount</span>
                    <span class="font-weight-bold text-danger">-$0.00</span>
                </div>
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <h5 class="mb-0 font-weight-bold text-gray-800">Total</h5>
                    <h3 class="mb-0 font-weight-bold text-purple" id="cartTotal">$0.00</h3>
                </div>
                
                <button class="btn btn-success checkout-btn d-flex justify-content-between align-items-center shadow">
                    <span>Charge</span>
                    <span id="btnTotal">$0.00 <i class="material-icons-outlined align-middle ml-2">arrow_forward</i></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = [];
    const currencySymbol = '{{ setting("default.currency", "$") }}'; // Fallback if setting fails
    
    // Quick formatter for JS
    function formatMoney(amount) {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
    }

    function addToCart(id, name, price) {
        const existing = cart.find(i => i.id === id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            }
            renderCart();
        }
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItemsContainer');
        const emptyMsg = document.getElementById('emptyCartMessage');
        const subtotalEl = document.getElementById('cartSubtotal');
        const totalEl = document.getElementById('cartTotal');
        const btnTotalEl = document.getElementById('btnTotal');
        
        let subtotal = 0;
        
        if (cart.length === 0) {
            container.innerHTML = '';
            container.appendChild(emptyMsg);
            emptyMsg.style.display = 'flex';
        } else {
            emptyMsg.style.display = 'none';
            let html = '';
            cart.forEach(item => {
                const itemTotal = item.price * item.qty;
                subtotal += itemTotal;
                
                html += `
                <div class="cart-item">
                    <div style="flex: 1;">
                        <h6 class="mb-1 font-weight-bold text-gray-800 text-sm">${item.name}</h6>
                        <span class="text-muted text-xs">${formatMoney(item.price)} each</span>
                    </div>
                    <div class="d-flex align-items-center mx-3">
                        <button class="btn btn-sm btn-light rounded-circle p-0" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;" onclick="updateQty(${item.id}, -1)">
                            <i class="material-icons-outlined" style="font-size: 16px;">remove</i>
                        </button>
                        <span class="mx-2 font-weight-bold text-gray-800">${item.qty}</span>
                        <button class="btn btn-sm btn-light rounded-circle p-0" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;" onclick="updateQty(${item.id}, 1)">
                            <i class="material-icons-outlined" style="font-size: 16px;">add</i>
                        </button>
                    </div>
                    <div class="text-right">
                        <span class="font-weight-bold text-gray-800">${formatMoney(itemTotal)}</span>
                    </div>
                </div>
                `;
            });
            container.innerHTML = html;
        }
        
        const formattedTotal = formatMoney(subtotal);
        subtotalEl.innerText = formattedTotal;
        totalEl.innerText = formattedTotal;
        btnTotalEl.innerHTML = `${formattedTotal} <i class="material-icons-outlined align-middle ml-2">arrow_forward</i>`;
    }
</script>
@endsection
