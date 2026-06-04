@extends('layouts.admin')

@section('title', 'Outlets / Locations')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">Store Settings</h2>
            <p class="text-muted small">Manage your physical locations and fulfillment centers.</p>
        </div>
        <div>
            <button class="btn btn-primary"><i class="material-icons-outlined align-middle mr-1">add</i> Add Location</button>
        </div>
    </div>

    <!-- Tabs (Settings Menu) -->
    <ul class="nav nav-tabs mb-4 border-0">
        <li class="nav-item">
            <a class="nav-link font-weight-bold text-muted border-0 bg-transparent px-4 pb-3" href="#">General Info</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold text-purple border-0 bg-transparent px-4 pb-3 border-bottom border-purple" href="#" style="border-bottom-width: 3px !important;">Outlet / Locations</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold text-muted border-0 bg-transparent px-4 pb-3" href="#">Currencies</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold text-muted border-0 bg-transparent px-4 pb-3" href="#">Taxes</a>
        </li>
    </ul>

    <!-- Locations List -->
    <div class="row">
        @foreach($outlets as $outlet)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-purple text-white rounded-circle d-flex justify-content-center align-items-center mr-3" style="width: 48px; height: 48px;">
                                <i class="material-icons-outlined">storefront</i>
                            </div>
                            <div>
                                <h5 class="font-weight-bold text-gray-800 mb-0">{{ $outlet['name'] }}</h5>
                                <span class="text-muted text-sm">{{ $outlet['location'] }}</span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link text-muted p-0" type="button" data-toggle="dropdown">
                                <i class="material-icons-outlined">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Edit Details</a>
                                <a class="dropdown-item text-danger" href="#">Deactivate</a>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-xs text-uppercase text-muted font-weight-bold block">Current Inventory</span>
                            <span class="h6 font-weight-bold text-gray-800">{{ number_format($outlet['stock_count']) }} Items</span>
                        </div>
                        <div>
                            @if($outlet['status'] == 'active')
                                <span class="badge badge-soft-success px-3 py-2 rounded-pill">Active Location</span>
                            @else
                                <span class="badge badge-soft-danger px-3 py-2 rounded-pill">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
