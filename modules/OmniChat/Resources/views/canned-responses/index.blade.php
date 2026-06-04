@extends('layouts.admin')

@section('title', 'Canned Responses')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0 text-gray-800 font-weight-bold">Canned Responses</h2>
            <p class="text-muted small">Create quick-reply templates to rapidly answer common questions.</p>
        </div>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addResponseModal">
            <i class="material-icons-outlined align-middle mr-1">add_circle</i> Add Template
        </button>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center table-flush table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Title</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Shortcut</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted">Message Body</th>
                            <th class="font-weight-bold text-uppercase text-xs text-muted text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($responses as $response)
                            <tr>
                                <td class="font-weight-bold text-gray-800">{{ $response->title }}</td>
                                <td>
                                    @if($response->shortcut)
                                        <span class="badge badge-light px-2 py-1 rounded border text-monospace">{{ $response->shortcut }}</span>
                                    @else
                                        <span class="text-muted text-xs">None</span>
                                    @endif
                                </td>
                                <td><span class="text-truncate d-inline-block text-muted" style="max-width: 300px;" title="{{ $response->body }}">{{ $response->body }}</span></td>
                                <td class="text-right">
                                    <form action="{{ route('omni-chat.canned_responses.destroy', $response->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this response?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-0" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="material-icons-outlined text-sm">delete</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <div class="mb-3"><i class="material-icons-outlined text-4xl opacity-50">chat</i></div>
                                    <h6 class="font-weight-bold text-gray-800">No canned responses found</h6>
                                    <p class="small text-muted mb-0">Create your first template to speed up customer replies.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addResponseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold">New Quick Reply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('omni-chat.canned_responses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-muted font-weight-bold small">Template Title</label>
                        <input type="text" name="title" class="form-control bg-light border-0" placeholder="e.g. Return Policy" required style="border-radius: 10px;">
                    </div>
                    <div class="form-group">
                        <label class="text-muted font-weight-bold small">Slash Shortcut (optional)</label>
                        <input type="text" name="shortcut" class="form-control bg-light border-0" placeholder="e.g. /returns" style="border-radius: 10px;">
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-muted font-weight-bold small">Message Body</label>
                        <textarea name="body" class="form-control bg-light border-0" rows="4" placeholder="Enter the full message text here..." required style="border-radius: 10px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Save Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
