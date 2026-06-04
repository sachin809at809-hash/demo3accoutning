@extends('layouts.admin')

@section('title', 'Transactions Ledger')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">Transactions Ledger</h2>
            <p class="text-muted small">Financial records for all successful e-commerce orders and manual POS transactions.</p>
        </div>
        <div>
            <button class="btn btn-outline-purple mr-2"><i class="material-icons-outlined align-middle mr-1">file_download</i> Export CSV</button>
            <button class="btn btn-primary"><i class="material-icons-outlined align-middle mr-1">sync</i> Sync with Accounting</button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2 border-0" style="border-left: 4px solid #10b981 !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue (30 Days)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">@money($transactions->sum('total'), setting('default.currency'), true)</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-success" style="font-size: 2rem; opacity: 0.5;">account_balance_wallet</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2 border-0" style="border-left: 4px solid #7c3aed !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Transaction Volume</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactions->count() }} Payments</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons-outlined text-primary" style="font-size: 2rem; opacity: 0.5;">receipt</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center table-flush table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Date</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Transaction ID</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Source Order</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Customer</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Amount</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-center">Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td>
                                    <span class="text-sm font-weight-bold text-gray-700">{{ $trx->created_at->format('M d, Y') }}</span><br>
                                    <span class="text-xs text-muted">{{ $trx->created_at->format('h:i A') }}</span>
                                </td>
                                <td><span class="text-monospace text-sm text-muted">TXN-{{ strtoupper(Str::random(8)) }}</span></td>
                                <td><a href="{{ route('ecommerce.orders.show', $trx->id) }}" class="font-weight-bold text-purple">#{{ $trx->order_number }}</a></td>
                                <td>
                                    <h6 class="mb-0 font-weight-bold text-gray-800 text-sm">{{ $trx->customer_name }}</h6>
                                </td>
                                <td><span class="text-sm font-weight-bold text-success">+@money($trx->total, setting('default.currency'), true)</span></td>
                                <td class="text-center">
                                    <span class="badge badge-soft-success px-3 py-1 rounded-pill"><i class="material-icons-outlined text-xs align-middle mr-1">check_circle</i> Paid</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <div class="mb-3"><i class="material-icons-outlined text-4xl opacity-50">account_balance</i></div>
                                    <h6 class="font-weight-bold text-gray-800">No transactions recorded</h6>
                                    <p class="small text-muted mb-4">Paid orders will appear here as financial ledger entries.</p>
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
