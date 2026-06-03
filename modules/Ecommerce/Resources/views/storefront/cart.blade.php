<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - QuickStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        input[type=number].no-spinners::-webkit-inner-spin-button, 
        input[type=number].no-spinners::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number].no-spinners { -moz-appearance:textfield; }
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
            </div>
        </div>
    </nav>

    <!-- Cart View -->
    <div class="pt-24 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Your Shopping Cart</h1>

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-8 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    @php $total = 0 @endphp
                    @if(session('cart') && count(session('cart')) > 0)
                        <ul class="divide-y divide-gray-100">
                            @foreach(session('cart') as $id => $details)
                                @php $total += $details['price'] * $details['quantity'] @endphp
                                <li class="p-6 flex flex-col sm:flex-row items-center gap-6 group hover:bg-gray-50 transition-colors">
                                    <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center">
                                        @if(isset($details['image']) && $details['image'])
                                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow text-center sm:text-left">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $details['name'] }}</h3>
                                        <p class="text-indigo-600 font-bold mt-1">@money($details['price'], setting('default.currency'), true)</p>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <form action="{{ route('ecommerce.store.cart.update') }}" method="POST" class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white h-10">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="button" class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); this.form.submit();">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-10 h-full text-center border-none focus:ring-0 font-semibold text-gray-900 p-0 m-0 no-spinners" onchange="this.form.submit()">
                                            <button type="button" class="w-8 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); this.form.submit();">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('ecommerce.store.cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="w-10 h-10 rounded-full text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center" title="Remove Item">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-12 text-center flex flex-col items-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-6">
                                <i class="fas fa-shopping-cart text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Your cart is empty</h3>
                            <p class="text-gray-500 mb-8">Looks like you haven't added anything to your cart yet.</p>
                            <a href="{{ route('ecommerce.store.index') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-md shadow-indigo-200">
                                Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Order Summary</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-900">@money($total, setting('default.currency'), true)</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600 border-b border-gray-100 pb-4">
                            <span>Shipping</span>
                            <span class="text-sm text-indigo-600 font-medium bg-indigo-50 px-2 py-1 rounded">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-xl font-extrabold text-gray-900">Total</span>
                            <span class="text-2xl font-extrabold text-indigo-600">@money($total, setting('default.currency'), true)</span>
                        </div>
                    </div>

                    @if(session('cart') && count(session('cart')) > 0)
                        <a href="{{ route('ecommerce.store.checkout') }}" class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all transform active:scale-95 shadow-lg shadow-indigo-200">
                            Proceed to Checkout <i class="fas fa-arrow-right"></i>
                        </a>
                    @else
                        <button disabled class="w-full h-14 bg-gray-100 text-gray-400 font-bold rounded-xl flex items-center justify-center cursor-not-allowed">
                            Proceed to Checkout
                        </button>
                    @endif
                    
                    <div class="mt-6 flex items-center justify-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-lock"></i> Secure SSL Checkout
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
