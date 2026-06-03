<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - QuickStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
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
                <div class="flex items-center gap-4">
                    <a href="{{ route('ecommerce.store.cart') }}" class="relative p-2 text-gray-600 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-pink-500 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Detail -->
    <div class="pt-24 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('error'))
            <div class="mb-8 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <nav class="flex text-sm text-gray-500 mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('ecommerce.store.index') }}" class="hover:text-indigo-600 transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="hover:text-indigo-600 transition-colors">{{ $product->category->name ?? 'General' }}</span>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-gray-900 font-medium truncate">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
            <!-- Image Gallery -->
            <div class="w-full md:w-1/2 bg-gray-50 p-8 flex items-center justify-center border-b md:border-b-0 md:border-r border-gray-100 min-h-[400px]">
                @if($product->images->count() > 0)
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="max-w-full max-h-[500px] object-contain rounded-lg drop-shadow-xl">
                @else
                    <i class="fas fa-image text-8xl text-gray-300"></i>
                @endif
            </div>

            <!-- Product Info -->
            <div class="w-full md:w-1/2 p-8 lg:p-12 flex flex-col">
                <span class="text-indigo-600 font-bold uppercase tracking-wider text-sm mb-2">{{ $product->category->name ?? 'General' }}</span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-3xl font-extrabold text-gray-900">@money($product->price, setting('default.currency'), true)</span>
                    @if($product->stock_quantity > 0)
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-bold rounded-full">In Stock</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-bold rounded-full">Out of Stock</span>
                    @endif
                </div>

                <p class="text-gray-600 leading-relaxed mb-8 whitespace-pre-line">{{ $product->description ?: 'No description available for this product.' }}</p>

                <div class="mt-auto">
                    <form action="{{ route('ecommerce.store.cart.add', $product->id) }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                        @csrf
                        <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden bg-white w-32 h-14">
                            <button type="button" class="w-10 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors" onclick="document.getElementById('qty').stepDown()">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-12 h-full text-center border-none focus:ring-0 text-lg font-semibold text-gray-900 p-0 m-0 no-spinners">
                            <button type="button" class="w-10 h-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors" onclick="document.getElementById('qty').stepUp()">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                        
                        <button type="submit" class="h-14 flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all transform active:scale-95 shadow-lg shadow-indigo-200" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            {{ $product->stock_quantity <= 0 ? 'Sold Out' : 'Add to Cart' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        /* Hide number input arrows */
        input[type=number].no-spinners::-webkit-inner-spin-button, 
        input[type=number].no-spinners::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number].no-spinners { -moz-appearance:textfield; }
    </style>
</body>
</html>
