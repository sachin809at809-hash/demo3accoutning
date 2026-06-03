<x-layouts.admin>
    <x-slot name="title">
        OmniChat Inbox
    </x-slot>

    <x-slot name="content">
        <div class="row">
            <!-- Sidebar: Conversations List -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Inbox</h4>
                        
                        <!-- Platform Filters -->
                        <div class="mt-3 d-flex flex-wrap" style="gap: 10px; padding-bottom: 10px;">
                            <a href="{{ route('omni-chat.inbox') }}" class="btn btn-sm shadow-sm rounded-pill font-weight-bold px-3 {{ !$platform ? 'btn-primary' : 'btn-outline-primary' }}" style="transition: all 0.2s;">
                                <i class="fas fa-layer-group mr-1"></i> All
                            </a>
                            
                            @php
                                $allPlatforms = [
                                    'facebook' => ['label' => 'Facebook', 'icon' => 'fab fa-facebook'],
                                    'instagram' => ['label' => 'Instagram', 'icon' => 'fab fa-instagram'],
                                    'tiktok' => ['label' => 'TikTok', 'icon' => 'fab fa-tiktok'],
                                    'linkedin' => ['label' => 'LinkedIn', 'icon' => 'fab fa-linkedin'],
                                    'mail' => ['label' => 'Email', 'icon' => 'fas fa-envelope'],
                                    'whatsapp_api' => ['label' => 'WhatsApp API', 'icon' => 'fab fa-whatsapp'],
                                    'whatsapp_qr' => ['label' => 'WhatsApp QR', 'icon' => 'fas fa-qrcode'],
                                ];
                            @endphp
                            
                            @foreach($allPlatforms as $apKey => $data)
                                <a href="{{ route('omni-chat.inbox', ['platform' => $apKey]) }}" class="btn btn-sm shadow-sm rounded-pill font-weight-bold px-3 {{ $platform == $apKey ? 'btn-primary' : 'btn-outline-primary' }}" style="transition: all 0.2s;">
                                    <i class="{{ $data['icon'] }} mr-1"></i> {{ $data['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($conversations as $conv)
                                <a href="{{ route('omni-chat.inbox.show', $conv->id) }}" class="list-group-item list-group-item-action {{ isset($conversation) && $conversation->id == $conv->id ? 'active' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $conv->name ?? $conv->external_id }}</h5>
                                        <small>{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : '' }}</small>
                                    </div>
                                    <p class="mb-1 text-muted text-sm">
                                        Via: <span class="badge badge-primary">{{ ucfirst($conv->channel->platform ?? 'Unknown') }}</span>
                                    </p>
                                </a>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">No conversations yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content: Active Conversation -->
            <div class="col-md-8">
                @if(isset($conversation))
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">{{ $conversation->name ?? $conversation->external_id }}</h4>
                            <span class="badge badge-info">{{ ucfirst($conversation->channel->platform) }}</span>
                        </div>
                        
                        <div class="card-body" style="height: 500px; overflow-y: auto;">
                            @foreach($conversation->messages as $msg)
                                <div class="mb-3 d-flex {{ $msg->direction == 'outgoing' ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="p-3 rounded {{ $msg->direction == 'outgoing' ? 'bg-primary text-white' : 'bg-light text-dark' }}" style="max-width: 75%;">
                                        {{ $msg->body }}
                                        <div class="mt-1" style="font-size: 0.75rem; opacity: 0.8;">
                                            {{ $msg->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="card-footer">
                            <form action="{{ route('omni-chat.inbox.reply', $conversation->id) }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="message" class="form-control" placeholder="Type your reply..." required autocomplete="off">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            Send <span class="material-icons" style="font-size: 16px; vertical-align: middle;">send</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card h-100 d-flex justify-content-center align-items-center">
                        <div class="text-center text-muted">
                            <span class="material-icons mb-3" style="font-size: 48px;">chat</span>
                            <h4>Select a conversation to start chatting</h4>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>
</x-layouts.admin>
