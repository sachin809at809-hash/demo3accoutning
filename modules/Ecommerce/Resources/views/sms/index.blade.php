@extends('layouts.admin')

@section('title', 'Automated SMS')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        @if(!$isConnected)
        <!-- API Connection Form -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5 text-center">
                    <div class="bg-purple text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-4" style="width: 80px; height: 80px;">
                        <i class="material-icons-outlined text-4xl">sms</i>
                    </div>
                    <h4 class="font-weight-bold text-gray-800 mb-3">Connect your SMS Provider</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">To enable automated SMS notifications for your orders, please enter your API Key from your SMS Provider (e.g. Twilio, Vonage, or AWS SNS).</p>
                    
                    <form action="{{ route('ecommerce.sms.connect') }}" method="POST" class="mx-auto" style="max-width: 400px;">
                        @csrf
                        <div class="form-group mb-4">
                            <input type="text" name="api_key" class="form-control form-control-lg text-center bg-light" placeholder="Enter API Key (e.g. sk_test_...)" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill font-weight-bold shadow-sm">
                            <i class="material-icons-outlined align-middle mr-1">link</i> Connect Provider
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <!-- SMS Quota Widget -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100 text-center shadow-sm border-0">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <div class="d-flex w-100 justify-content-end mb-2">
                        <span class="badge badge-soft-success px-3 py-1 rounded-pill"><i class="material-icons-outlined align-middle text-sm mr-1">check_circle</i> Connected</span>
                    </div>
                    <h5 class="font-weight-bold text-gray-800 mb-4">Your SMS Quota</h5>
                    
                    <!-- CSS Circular Progress Bar -->
                    <div class="position-relative d-inline-block mb-4" style="width: 150px; height: 150px;">
                        <svg viewBox="0 0 36 36" class="w-100 h-100">
                            <path class="text-gray-200" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                            <path class="text-purple" stroke-dasharray="{{ 100 - $percentage }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" />
                        </svg>
                        <div class="position-absolute d-flex flex-column justify-content-center align-items-center" style="top: 0; left: 0; right: 0; bottom: 0;">
                            <span class="h3 font-weight-bold mb-0 text-purple">{{ number_format($smsLeft) }}</span>
                            <span class="text-xs text-muted">Left</span>
                        </div>
                    </div>

                    <p class="text-muted text-sm mb-4">You have sent {{ number_format($smsSent) }} out of {{ number_format($smsLimit) }} messages this billing cycle.</p>
                    <button class="btn btn-outline-purple w-100 py-2 font-weight-bold"><i class="material-icons-outlined align-middle mr-1">shopping_cart</i> Purchase SMS</button>
                </div>
            </div>
        </div>

        <!-- SMS Events Table -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white py-4 d-flex flex-row align-items-center justify-content-between border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-gray-800">SMS Event Triggers</h6>
                    <button class="btn btn-primary btn-sm rounded-pill px-3"><i class="material-icons-outlined align-middle mr-1 text-sm">add</i> Custom Event</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush mb-0 border-top">
                            <thead class="thead-light">
                                <tr>
                                    <th class="font-weight-bold text-uppercase text-xs text-muted">Event Name</th>
                                    <th class="font-weight-bold text-uppercase text-xs text-muted">Message Template</th>
                                    <th class="font-weight-bold text-uppercase text-xs text-muted text-center">Status</th>
                                    <th class="font-weight-bold text-uppercase text-xs text-muted text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                <tr>
                                    <td class="font-weight-bold text-gray-800">{{ $event['name'] }}</td>
                                    <td class="text-sm text-muted text-truncate" style="max-width: 250px;">{{ $event['template'] }}</td>
                                    <td class="text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="switch-{{ $loop->index }}" {{ $event['is_active'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="switch-{{ $loop->index }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="text-purple mx-1 p-2 bg-light rounded-circle d-inline-flex"><i class="material-icons-outlined text-sm">edit</i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* Custom Switch overrides to match Theme Purple */
.custom-control-input:checked ~ .custom-control-label::before {
    border-color: #7c3aed !important;
    background-color: #7c3aed !important;
}
</style>
@endsection
