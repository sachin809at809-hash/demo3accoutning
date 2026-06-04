@extends('layouts.admin')

@section('title', 'Order Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">Incoming Orders</h2>
            <p class="text-muted small">Manage order fulfillment, shipments, and customer refunds.</p>
        </div>
        <div>
            <button class="btn btn-outline-purple mr-2"><i class="material-icons-outlined align-middle mr-1">print</i> Print Pick Lists</button>
            <button class="btn btn-primary"><i class="material-icons-outlined align-middle mr-1">add</i> Create Manual Order</button>
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
                    <input type="text" class="form-control bg-light border-left-0 pl-0" placeholder="Search order #, customer, email...">
                </div>
                <select class="custom-select custom-select-sm mr-2 text-muted bg-light" style="width: 150px;">
                    <option selected>All Statuses</option>
                    <option value="received">Received</option>
                    <option value="processing">Processing</option>
                    <option value="dispatched">Dispatched</option>
                    <option value="delivered">Delivered</option>
                </select>
                <select class="custom-select custom-select-sm text-muted bg-light" style="width: 150px;">
                    <option selected>Last 30 Days</option>
                    <option value="today">Today</option>
                    <option value="7days">Last 7 Days</option>
                </select>
            </div>
            <div>
                <button class="btn btn-sm btn-link text-muted"><i class="material-icons-outlined align-middle">filter_list</i> Advanced Filters</button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center table-flush table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkAllOrders">
                                    <label class="custom-control-label" for="checkAllOrders"></label>
                                </div>
                            </th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Order ID</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Customer</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Date & Time</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Total</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-center">Status</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="check-order-{{ $order->id }}">
                                        <label class="custom-control-label" for="check-order-{{ $order->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('ecommerce.orders.show', $order->id) }}" class="font-weight-bold text-purple">#{{ $order->order_number }}</a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-purple text-white rounded-circle d-flex justify-content-center align-items-center mr-2" style="width: 32px; height: 32px; font-size: 14px;">
                                            {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold text-gray-800 text-sm">{{ $order->customer_name }}</h6>
                                            <span class="text-xs text-muted">{{ $order->customer_email ?? 'No email' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm font-weight-bold text-gray-700">{{ $order->created_at->format('M d, Y') }}</span><br>
                                    <span class="text-xs text-muted">{{ $order->created_at->format('h:i A') }}</span>
                                </td>
                                <td><span class="text-sm font-weight-bold text-gray-800">@money($order->total, setting('default.currency'), true)</span></td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link p-0 border-0 bg-transparent dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @if($order->status == 'pending' || $order->status == 'received')
                                                <span class="badge badge-soft-warning px-3 py-1 rounded-pill">Received</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge badge-soft-info px-3 py-1 rounded-pill">Processing</span>
                                            @elseif($order->status == 'dispatched' || $order->status == 'shipped')
                                                <span class="badge badge-soft-primary px-3 py-1 rounded-pill">Dispatched</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge badge-soft-success px-3 py-1 rounded-pill">Delivered</span>
                                            @elseif($order->status == 'refunded')
                                                <span class="badge badge-soft-dark px-3 py-1 rounded-pill">Refunded</span>
                                            @else
                                                <span class="badge badge-soft-danger px-3 py-1 rounded-pill">Cancelled</span>
                                            @endif
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                                            <h6 class="dropdown-header text-xs font-weight-bold">Update Status</h6>
                                            <form action="{{ route('ecommerce.orders.update_status', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="dropdown-item py-2 text-sm"><i class="material-icons-outlined align-middle mr-2 text-muted text-sm">autorenew</i> Mark Processing</button>
                                            </form>
                                            <form action="{{ route('ecommerce.orders.update_status', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="dispatched">
                                                <button type="submit" class="dropdown-item py-2 text-sm"><i class="material-icons-outlined align-middle mr-2 text-muted text-sm">local_shipping</i> Mark Dispatched</button>
                                            </form>
                                            <form action="{{ route('ecommerce.orders.update_status', $order->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="delivered">
                                                <button type="submit" class="dropdown-item py-2 text-sm"><i class="material-icons-outlined align-middle mr-2 text-muted text-sm">check_circle</i> Mark Delivered</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted p-0" type="button" data-toggle="dropdown">
                                            <i class="material-icons-outlined">more_vert</i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0">
                                            <a class="dropdown-item py-2" href="{{ route('ecommerce.orders.show', $order->id) }}"><i class="material-icons-outlined align-middle mr-2 text-sm">visibility</i> View Details</a>
                                            <a class="dropdown-item py-2" href="#"><i class="material-icons-outlined align-middle mr-2 text-sm">receipt_long</i> Download Invoice</a>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('ecommerce.orders.update_status', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to refund this order?');">
                                                @csrf
                                                <input type="hidden" name="status" value="refunded">
                                                <button type="submit" class="dropdown-item py-2 text-danger border-0 bg-transparent" style="cursor: pointer;"><i class="material-icons-outlined align-middle mr-2 text-sm">undo</i> Refund Order</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <div class="mb-3"><i class="material-icons-outlined text-4xl opacity-50">shopping_bag</i></div>
                                    <h6 class="font-weight-bold text-gray-800">No incoming orders yet</h6>
                                    <p class="small text-muted mb-4">When customers purchase items from your store, they will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(count($orders) > 0)
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
            <span class="text-sm text-muted">Showing <strong>{{ count($orders) }}</strong> items</span>
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
