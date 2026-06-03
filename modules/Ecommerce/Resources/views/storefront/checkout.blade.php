<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - QuickStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        
        /* Floating labels */
        .floating-input { @apply block w-full px-4 pt-6 pb-2 text-gray-900 bg-white border border-gray-300 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent peer; }
        .floating-label { @apply absolute text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 peer-focus:text-indigo-600; }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 glass border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('ecommerce.store.index') }}" class="flex items-center gap-2">
                    <i class="fas fa-shopping-bag text-indigo-600 text-2xl"></i>
                    <span class="font-bold text-xl tracking-tight">QuickStore</span>
                </a>
                <div class="flex items-center text-sm font-medium text-gray-500">
                    <i class="fas fa-lock mr-2 text-indigo-500"></i> Secure Checkout
                </div>
            </div>
        </div>
    </nav>

    <!-- Checkout View -->
    <div class="pt-24 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="mb-8 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <!-- Form Section -->
            <div class="w-full lg:w-3/5">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Checkout</h1>
                
                <form action="{{ route('ecommerce.store.checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <!-- Contact Information -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Contact Information</h2>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                            <div class="relative">
                                <input type="text" name="customer_name" id="customer_name" class="floating-input" placeholder=" " required>
                                <label for="customer_name" class="floating-label">Full Name</label>
                            </div>
                            <div class="relative">
                                <input type="email" name="customer_email" id="customer_email" class="floating-input" placeholder=" " required>
                                <label for="customer_email" class="floating-label">Email Address</label>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping details -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Shipping Details</h2>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                            <div class="relative">
                                <textarea name="shipping_address" id="shipping_address" rows="3" class="floating-input" placeholder=" " required></textarea>
                                <label for="shipping_address" class="floating-label">Full Delivery Address</label>
                            </div>
                            
                            <div class="relative">
                                <select name="zone_id" id="zone_id" class="floating-input" style="padding-top: 1.5rem;" required>
                                    <option value="" disabled selected></option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }} (+@money($zone->delivery_fee, setting('default.currency'), true))</option>
                                    @endforeach
                                </select>
                                <label for="zone_id" class="floating-label">Quick-Commerce Delivery Zone</label>
                            </div>

                            <div class="relative">
                                <textarea name="notes" id="notes" rows="2" class="floating-input" placeholder=" "></textarea>
                                <label for="notes" class="floating-label">Delivery Instructions (Optional)</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h2>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center gap-4 p-4 border border-indigo-200 bg-indigo-50 rounded-xl cursor-pointer">
                                <input type="radio" name="payment_method" id="payment_cod" value="cod" class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" checked>
                                <label for="payment_cod" class="flex-grow flex items-center justify-between cursor-pointer font-bold text-indigo-900">
                                    <span>Cash on Delivery</span>
                                    <i class="fas fa-money-bill-wave text-indigo-400 text-xl"></i>
                                </label>
                            </div>
                            <p class="text-sm text-gray-500 mt-3 px-4">Pay in cash or transfer when your quick-commerce driver arrives.</p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary Section -->
            <div class="w-full lg:w-2/5">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-24 overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Order Summary</h3>
                    </div>
                    
                    <div class="p-6">
                        @php $subtotal = 0; @endphp
                        
                        <div class="space-y-4 max-h-60 overflow-y-auto mb-6 pr-2">
                            @foreach($cart as $id => $details)
                                @php $subtotal += $details['price'] * $details['quantity']; @endphp
                                <div class="flex gap-4 items-start">
                                    <div class="relative w-16 h-16 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                        @if(isset($details['image']) && $details['image'])
                                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
                                        @endif
                                        <span class="absolute -top-2 -right-2 bg-gray-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">{{ $details['quantity'] }}</span>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="text-sm font-bold text-gray-900 line-clamp-2">{{ $details['name'] }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">@money($details['price'], setting('default.currency'), true)</p>
                                    </div>
                                    <div class="text-right font-semibold text-gray-900">
                                        @money($details['price'] * $details['quantity'], setting('default.currency'), true)
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-3 py-4 border-t border-gray-100">
                            <div class="flex justify-between items-center text-gray-600 text-sm">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-900">@money($subtotal, setting('default.currency'), true)</span>
                            </div>
                            <div class="flex justify-between items-center text-gray-600 text-sm">
                                <span>Delivery Fee</span>
                                <span class="font-semibold text-indigo-600" id="summary_delivery">Select a zone</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-100 mb-6">
                            <span class="text-lg font-extrabold text-gray-900">Total</span>
                            <span class="text-2xl font-extrabold text-indigo-600" id="summary_total">@money($subtotal, setting('default.currency'), true)</span>
                        </div>

                        <button type="button" onclick="document.getElementById('checkout-form').submit();" class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all transform active:scale-95 shadow-lg shadow-indigo-200">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store zone prices from server to update totals dynamically
        const zonePrices = {
            @foreach($zones as $zone)
                "{{ $zone->id }}": {{ $zone->delivery_fee }},
            @endforeach
        };
        const subtotal = {{ $subtotal }};
        const currencySymbol = '{{ setting('default.currency') }}'; // Simplified formatting for client-side

        document.getElementById('zone_id').addEventListener('change', function() {
            const zoneId = this.value;
            const deliveryFee = zonePrices[zoneId] || 0;
            const total = subtotal + deliveryFee;
            
            // Format numbers nicely (simple formatting for MVP)
            document.getElementById('summary_delivery').innerText = '$' + deliveryFee.toFixed(2);
            document.getElementById('summary_total').innerText = '$' + total.toFixed(2);
        });
    </script>
</body>
</html>
