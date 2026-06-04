@extends('layouts.admin')

@section('title', 'Blanxer SMS Automation')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- SMS Quota Widget -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
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
                    <button class="btn btn-primary w-100 py-2"><i class="material-icons-outlined align-middle mr-1">shopping_cart</i> Purchase SMS</button>
                </div>
            </div>
        </div>

        <!-- SMS Events Table -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-gray-800">SMS Event Triggers</h6>
                    <button class="btn btn-outline-purple btn-sm"><i class="material-icons-outlined align-middle mr-1 text-sm">add</i> Custom Event</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Event Name</th>
                                    <th>Message Template</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                <tr>
                                    <td class="font-weight-bold">{{ $event['name'] }}</td>
                                    <td class="text-sm text-muted text-truncate" style="max-width: 250px;">{{ $event['template'] }}</td>
                                    <td class="text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="switch-{{ $loop->index }}" {{ $event['is_active'] ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="switch-{{ $loop->index }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="text-purple mx-1"><i class="material-icons-outlined">edit</i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Switch overrides to match Blanxer Purple */
.custom-control-input:checked ~ .custom-control-label::before {
    border-color: #7c3aed !important;
    background-color: #7c3aed !important;
}
</style>
@endsection
