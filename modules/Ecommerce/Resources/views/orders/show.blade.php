<x-layouts.admin>
    <x-slot name="title">
        Order Details: {{ $order->order_number }}
    </x-slot>

    <x-slot name="content">
        <div class="row">
            <div class="col-md-8">
                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Items Ordered</h4>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->product_name }}</strong></td>
                                        <td class="text-center">@money($item->price, setting('default.currency'), true)</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right">@money($item->total, setting('default.currency'), true)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right text-muted">Subtotal:</td>
                                    <td class="text-right">@money($order->subtotal, setting('default.currency'), true)</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right text-muted">Delivery Fee:</td>
                                    <td class="text-right text-success">+ @money($order->delivery_fee, setting('default.currency'), true)</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right fw-bold h5 border-top pt-3">Total Paid:</td>
                                    <td class="text-right fw-bold h5 text-primary border-top pt-3">@money($order->total, setting('default.currency'), true)</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Fulfillment Actions -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0 text-white">Fulfillment Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            Current Status: 
                            @if($order->status == 'pending')
                                <span class="badge badge-warning text-lg px-3 py-2">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge badge-info text-lg px-3 py-2">Processing</span>
                            @elseif($order->status == 'shipped')
                                <span class="badge badge-primary text-lg px-3 py-2">Shipped</span>
                            @elseif($order->status == 'delivered')
                                <span class="badge badge-success text-lg px-3 py-2">Delivered</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge badge-danger text-lg px-3 py-2">Cancelled</span>
                            @endif
                        </div>

                        <form action="{{ route('ecommerce.orders.update_status', $order->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Update Status To:</label>
                                <select name="status" class="form-control mb-2">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped (On the way)</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered (Completed)</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Order</button>
                        </form>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customer & Delivery</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                        <p class="mb-3 text-muted">
                            <a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email ?? 'No email provided' }}</a>
                        </p>
                        
                        <h6 class="text-muted text-uppercase text-xs mt-4 mb-2">Delivery Address</h6>
                        <p class="mb-3">{{ $order->shipping_address }}</p>
                        
                        @if($order->zone)
                            <h6 class="text-muted text-uppercase text-xs mt-4 mb-2">Quick-Commerce Zone</h6>
                            <p class="mb-3 badge badge-outline-primary">{{ $order->zone->name }}</p>
                        @endif

                        @if($order->notes)
                            <h6 class="text-muted text-uppercase text-xs mt-4 mb-2">Delivery Notes</h6>
                            <div class="alert alert-warning mb-0 p-2">
                                {{ $order->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.admin>
