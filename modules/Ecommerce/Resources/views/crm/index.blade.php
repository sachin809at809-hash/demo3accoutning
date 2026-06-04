@extends('layouts.admin')

@section('title', 'CRM & Leads')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">CRM Dashboard</h2>
            <p class="text-muted small">Manage your {{ $type == 'leads' ? 'potential leads' : 'customer relationships' }} and track lifetime value.</p>
        </div>
        <div>
            <button class="btn btn-outline-purple mr-2"><i class="material-icons-outlined align-middle mr-1">mail</i> Broadcast Email</button>
            <button class="btn btn-primary"><i class="material-icons-outlined align-middle mr-1">person_add</i> Add Customer</button>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-pills mb-4" id="crm-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $type == 'customers' ? 'active bg-purple text-white' : 'text-muted' }} rounded-pill px-4 mr-2" href="{{ route('ecommerce.crm.index', ['type' => 'customers']) }}">
                <i class="material-icons-outlined align-middle mr-1 text-sm">people</i> Active Customers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $type == 'leads' ? 'active bg-purple text-white' : 'text-muted' }} rounded-pill px-4" href="{{ route('ecommerce.crm.index', ['type' => 'leads']) }}">
                <i class="material-icons-outlined align-middle mr-1 text-sm">leaderboard</i> Marketing Leads
            </a>
        </li>
    </ul>

    <!-- Metrics Row -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase text-muted mb-1">Total {{ ucfirst($type) }}</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ count($customers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-4xl text-purple opacity-25">groups</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase text-muted mb-1">Avg. Lifetime Value</div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                @money(count($customers) > 0 ? $customers->sum('lifetime_value') / count($customers) : 0, setting('default.currency'), true)
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-4xl text-success opacity-25">trending_up</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase text-muted mb-1">Churn Risk</div>
                            <div class="h3 mb-0 font-weight-bold text-warning">0%</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-4xl text-warning opacity-25">warning</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-gray-800">Directory</h6>
            <div class="input-group input-group-sm" style="width: 250px;">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search by name or email...">
                <div class="input-group-append">
                    <button class="btn btn-purple" type="button">
                        <i class="material-icons-outlined text-sm text-white">search</i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center table-flush table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Customer Name</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Email Contact</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-center">Total Orders</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-right">Lifetime Value</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light text-purple rounded-circle d-flex justify-content-center align-items-center mr-3" style="width: 40px; height: 40px; font-weight: bold; font-size: 16px;">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold text-gray-800">{{ $customer->name }}</h6>
                                            <span class="text-xs text-muted">Active since {{ \Carbon\Carbon::parse($customer->last_order)->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td><a href="mailto:{{ $customer->email }}" class="text-purple font-weight-bold text-sm">{{ $customer->email }}</a></td>
                                <td class="text-center">
                                    <span class="badge badge-soft-info px-3 py-1 rounded-pill">{{ $customer->total_orders }} orders</span>
                                </td>
                                <td class="text-right">
                                    <span class="text-sm font-weight-bold text-success">@money($customer->lifetime_value, setting('default.currency'), true)</span>
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">View Profile</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="mb-3"><i class="material-icons-outlined text-4xl opacity-50">person_off</i></div>
                                    <h6 class="font-weight-bold text-gray-800">No {{ $type }} found</h6>
                                    <p class="small text-muted mb-0">Once orders are placed or leads are captured, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
