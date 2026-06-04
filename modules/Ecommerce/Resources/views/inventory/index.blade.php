@extends('layouts.admin')

@section('title', 'Inventory Overview')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">Inventory Overview</h2>
            <p class="text-muted small">Manage your multi-location stock, batches, and variants.</p>
        </div>
        <div>
            <button class="btn btn-outline-purple mr-2"><i class="material-icons-outlined align-middle mr-1">file_download</i> Export</button>
            <button class="btn btn-primary"><i class="material-icons-outlined align-middle mr-1">add</i> Receive Stock</button>
        </div>
    </div>

    <!-- Top Metrics Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Inventory Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@money($metrics['total_value'], setting('default.currency'), true)</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-purple text-3xl opacity-50">account_balance_wallet</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Inventory Cost</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@money($metrics['total_cost'], setting('default.currency'), true)</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-purple text-3xl opacity-50">shopping_cart</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total SKUs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($metrics['total_skus']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-purple text-3xl opacity-50">category</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Inventory Quantity</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($metrics['total_quantity']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-purple text-3xl opacity-50">inventory_2</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row">
        <!-- Inventory Health -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-gray-800">Inventory Health</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-soft-danger px-3 py-2 rounded-pill"><i class="material-icons-outlined align-middle text-sm">error_outline</i> {{ $health['out_of_stock'] }} Out of Stock SKUs</span>
                        </div>
                        <a href="#" class="text-purple text-sm font-weight-bold">View details &rarr;</a>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-soft-warning px-3 py-2 rounded-pill"><i class="material-icons-outlined align-middle text-sm">warning_amber</i> {{ $health['low_stock'] }} Low Stocks</span>
                        </div>
                        <a href="#" class="text-purple text-sm font-weight-bold">View details &rarr;</a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-soft-success px-3 py-2 rounded-pill"><i class="material-icons-outlined align-middle text-sm">inventory</i> {{ $health['dead_stock'] }} Dead Stock</span>
                        </div>
                        <a href="#" class="text-purple text-sm font-weight-bold">View details &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movement Analysis -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-gray-800">Stock Movement Analysis</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm mb-4">Analytics on product velocity and turnover rates.</p>
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">Fast Moving (65%)</div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Average (25%)</div>
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">Slow (10%)</div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="text-center">
                            <div class="h4 font-weight-bold text-gray-800 mb-0">12 Days</div>
                            <span class="text-muted text-xs text-uppercase">Avg. Turnover Time</span>
                        </div>
                        <div class="text-center">
                            <div class="h4 font-weight-bold text-gray-800 mb-0">8.5x</div>
                            <span class="text-muted text-xs text-uppercase">Turnover Ratio</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
