@extends('layouts.admin')

@section('title', 'Products Catalog')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">Manage Products</h2>
            <p class="text-muted small">View, edit, and organize your product catalog.</p>
        </div>
        <div>
            <button class="btn btn-outline-purple mr-2"><i class="material-icons-outlined align-middle mr-1">import_export</i> Import/Export</button>
            <a href="{{ route('ecommerce.products.create') }}" class="btn btn-primary">
                <i class="material-icons-outlined align-middle mr-1">add</i> Add New Product
            </a>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm mr-3" style="width: 250px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="material-icons-outlined text-sm">search</i></span>
                    </div>
                    <input type="text" class="form-control bg-light border-left-0 pl-0" placeholder="Search products...">
                </div>
                <select class="custom-select custom-select-sm mr-2 text-muted bg-light" style="width: 150px;">
                    <option selected>All Categories</option>
                    <option value="1">Electronics</option>
                    <option value="2">Apparel</option>
                </select>
                <select class="custom-select custom-select-sm text-muted bg-light" style="width: 150px;">
                    <option selected>All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <button class="btn btn-sm btn-link text-muted"><i class="material-icons-outlined align-middle">filter_list</i> Advanced</button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center table-flush table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAll">
                                    <label class="custom-control-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Product Details</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Category</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Price</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Inventory</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Status</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="check-{{ $product->id }}">
                                        <label class="custom-control-label" for="check-{{ $product->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded d-flex justify-content-center align-items-center mr-3" style="width: 48px; height: 48px;">
                                            <i class="material-icons-outlined text-muted">image</i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold text-gray-800">{{ $product->name }}</h6>
                                            <span class="text-xs text-muted">SKU: {{ $product->sku ?? strtoupper(Str::random(8)) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-sm font-weight-bold text-gray-700">{{ $product->category ? $product->category->name : 'Uncategorized' }}</span></td>
                                <td><span class="text-sm font-weight-bold text-gray-800">@money($product->price, setting('default.currency'), true)</span></td>
                                <td>
                                    @if($product->stock_quantity > 10)
                                        <span class="text-sm font-weight-bold text-success"><i class="material-icons-outlined align-middle text-xs mr-1">check_circle</i> {{ $product->stock_quantity }} in stock</span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="text-sm font-weight-bold text-warning"><i class="material-icons-outlined align-middle text-xs mr-1">warning_amber</i> {{ $product->stock_quantity }} low stock</span>
                                    @else
                                        <span class="text-sm font-weight-bold text-danger"><i class="material-icons-outlined align-middle text-xs mr-1">error_outline</i> Out of stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-soft-success px-3 py-1 rounded-pill">Active</span>
                                    @else
                                        <span class="badge badge-soft-secondary px-3 py-1 rounded-pill">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" type="button" data-toggle="dropdown">
                                            <i class="material-icons-outlined">more_vert</i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                                            <a class="dropdown-item py-2" href="{{ route('ecommerce.products.edit', $product->id) }}"><i class="material-icons-outlined align-middle mr-2 text-sm">edit</i> Edit Details</a>
                                            <a class="dropdown-item py-2" href="#"><i class="material-icons-outlined align-middle mr-2 text-sm">content_copy</i> Duplicate</a>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('ecommerce.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent" style="cursor: pointer;"><i class="material-icons-outlined align-middle mr-2 text-sm">delete</i> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <div class="mb-3"><i class="material-icons-outlined text-4xl opacity-50">inventory_2</i></div>
                                    <h6 class="font-weight-bold text-gray-800">No products found</h6>
                                    <p class="small text-muted mb-4">You haven't added any products to your catalog yet.</p>
                                    <a href="{{ route('ecommerce.products.create') }}" class="btn btn-primary btn-sm px-4 rounded-pill">Add your first product</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(count($products) > 0)
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="text-sm text-muted">Showing <strong>{{ count($products) }}</strong> items</span>
            <!-- Pagination Placeholder -->
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
                <li class="page-item active"><a class="page-link bg-purple border-purple" href="#">1</a></li>
                <li class="page-item"><a class="page-link text-purple" href="#">2</a></li>
                <li class="page-item"><a class="page-link text-purple" href="#">&raquo;</a></li>
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection
