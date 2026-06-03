<x-layouts.admin>
    <x-slot name="title">
        Products Catalog
    </x-slot>

    <x-slot name="content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Manage Products</h3>
                <a href="{{ route('ecommerce.products.create') }}" class="btn btn-primary">
                    <span class="material-icons">add</span> Add New Product
                </a>
            </div>
            
            <div class="card-body p-0 border-top-0">
                <div class="table-responsive">
                    <table class="table table-flush table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">SKU: {{ $product->sku ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $product->category ? $product->category->name : 'Uncategorized' }}</td>
                                    <td>@money($product->price, setting('default.currency'), true)</td>
                                    <td>
                                        @if($product->stock_quantity > 10)
                                            <span class="badge badge-success">{{ $product->stock_quantity }} in stock</span>
                                        @elseif($product->stock_quantity > 0)
                                            <span class="badge badge-warning">{{ $product->stock_quantity }} low stock</span>
                                        @else
                                            <span class="badge badge-danger">Out of stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{ route('ecommerce.products.edit', $product->id) }}">Edit</a>
                                                <form action="{{ route('ecommerce.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger border-0 bg-transparent" style="cursor: pointer;">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No products found. Click "Add New Product" to get started!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.admin>
