<x-layouts.admin>
    <x-slot name="title">
        Edit Product: {{ $product->name }}
    </x-slot>

    <x-slot name="content">
        <form action="{{ route('ecommerce.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <!-- General Details -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">General Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Product Name *</label>
                                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $product->name) }}">
                            </div>
                            
                            <div class="form-group mt-3">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="form-group mt-3">
                                <label for="image">Product Image</label>
                                @if($product->images->count() > 0)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" style="max-width: 150px; border-radius: 5px;">
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <small class="text-muted">Upload a new image to replace the current one.</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pricing & Inventory -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="card-title">Pricing & Inventory</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="price">Regular Price *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="price" id="price" class="form-control" required value="{{ old('price', $product->price) }}">
                                    </div>
                                </div>
                                
                                <div class="col-md-6 form-group">
                                    <label for="stock_quantity">Stock Quantity *</label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" required value="{{ old('stock_quantity', $product->stock_quantity) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Organization -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Organization</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">Select Category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active & Visible</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top">
                            <button type="submit" class="btn btn-primary w-100">Update Product</button>
                            <a href="{{ route('ecommerce.products.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-slot>
</x-layouts.admin>
