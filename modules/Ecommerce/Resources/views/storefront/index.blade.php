<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Store</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .gradient-text { background: linear-gradient(135deg, #4f46e5 0%, #ec4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
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

    <!-- Hero Section -->
    <div class="pt-24 pb-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl font-extrabold tracking-tight mb-4 text-gray-900">
                Discover <span class="gradient-text">Amazing</span> Products
            </h1>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                Quality items selected just for you, with lightning-fast delivery to your door.
            </p>
        </div>
    </div>

    <!-- Catalog Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar (Categories) -->
            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-indigo-600 font-semibold block px-3 py-2 rounded-lg bg-indigo-50">All Products</a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="#" class="text-gray-600 hover:text-indigo-600 hover:bg-gray-50 block px-3 py-2 rounded-lg transition-colors">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="w-full md:w-3/4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="product-card bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex flex-col">
                            <a href="{{ route('ecommerce.store.product', $product->slug) }}" class="block relative h-60 overflow-hidden bg-gray-50 group">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-image text-5xl"></i>
                                    </div>
                                @endif
                                
                                @if($product->stock_quantity <= 0)
                                    <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Out of Stock</div>
                                @endif
                            </a>
                            <div class="p-5 flex flex-col flex-grow">
                                <p class="text-sm text-indigo-500 font-medium mb-1">{{ $product->category->name ?? 'General' }}</p>
                                <a href="{{ route('ecommerce.store.product', $product->slug) }}" class="text-lg font-bold text-gray-900 mb-2 hover:text-indigo-600 transition-colors line-clamp-2">{{ $product->name }}</a>
                                
                                <div class="mt-auto pt-4 flex items-center justify-between">
                                    <span class="text-xl font-extrabold text-gray-900">@money($product->price, setting('default.currency'), true)</span>
                                    
                                    @if($product->stock_quantity > 0)
                                        <form action="{{ route('ecommerce.store.cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors" title="Add to Cart">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="h-10 w-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-gray-100">
                            <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-bold text-gray-900">No products found</h3>
                            <p class="text-gray-500 mt-2">Check back later for new arrivals.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
