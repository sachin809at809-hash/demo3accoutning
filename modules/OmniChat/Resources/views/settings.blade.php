<x-layouts.admin>
    <x-slot name="title">
        OmniChat Settings & Integrations
    </x-slot>

    <x-slot name="content">
        @php
            $getChannel = function($platform) use ($channels) {
                return $channels->firstWhere('platform', $platform);
            };
        @endphp

        <style>
            .platform-card {
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                overflow: hidden;
            }
            .platform-card:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.1), 0 15px 15px -10px rgba(0, 0, 0, 0.04);
            }
            .platform-header {
                padding: 1.5rem;
                color: white;
                border-bottom: none;
                position: relative;
                overflow: hidden;
            }
            .platform-header::after {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: linear-gradient(rgba(255,255,255,0.1), rgba(255,255,255,0));
                pointer-events: none;
            }
            .bg-facebook { background: linear-gradient(135deg, #1877F2, #0d5cbf); }
            .bg-instagram { background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); }
            .bg-tiktok { background: linear-gradient(135deg, #000000, #333333); }
            .bg-linkedin { background: linear-gradient(135deg, #0A66C2, #004182); }
            .bg-mail { background: linear-gradient(135deg, #FF9800, #F57C00); }
            .bg-whatsapp { background: linear-gradient(135deg, #25D366, #128C7E); }
            
            .form-control {
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                padding: 0.75rem 1rem;
                background-color: #f8fafc;
                transition: all 0.3s ease;
            }
            .form-control:focus {
                border-color: #4f46e5;
                background-color: #ffffff;
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            }
            .btn-save {
                border-radius: 12px;
                font-weight: 700;
                padding: 0.75rem 1.5rem;
                width: 100%;
                text-transform: uppercase;
                letter-spacing: 1px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            .btn-save:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 15px rgba(0,0,0,0.15);
            }
            .status-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 800;
                background: rgba(255, 255, 255, 0.25);
                backdrop-filter: blur(5px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
        </style>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-5 border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-5 text-center" style="background: linear-gradient(to right, #f8fafc, #f1f5f9); border-radius: 15px;">
                        <i class="fas fa-project-diagram text-primary mb-3" style="font-size: 3rem;"></i>
                        <h2 class="font-weight-bold text-dark">OmniChat Integrations</h2>
                        <p class="text-muted lead mb-0">Connect your messaging platforms to route all customer inquiries into one unified inbox.</p>
                    </div>
                </div>
            </div>

            <!-- Facebook -->
            @php $fb = $getChannel('facebook'); @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 platform-card">
                    <div class="card-header platform-header bg-facebook d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fab fa-facebook mr-2"></i> Facebook</h4>
                        @if($fb && $fb->is_active)<span class="status-badge"><i class="fas fa-check-circle mr-1"></i> Connected</span>@endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('omni-chat.settings.update') }}" method="POST" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="platform" value="facebook">
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Page Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Acme Corp" value="{{ $fb->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Page ID</label>
                                <input type="text" name="identifier" class="form-control" placeholder="Facebook Page ID" value="{{ $fb->identifier ?? '' }}" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-muted font-weight-bold small">Page Access Token</label>
                                <input type="password" name="api_key" class="form-control" placeholder="EAA..." value="{{ $fb->credentials['api_key'] ?? '' }}" required>
                            </div>
                            <div class="mt-auto">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="fb_active" value="1" {{ ($fb && $fb->is_active) || !$fb ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="fb_active">Enable this channel</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-save">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Instagram -->
            @php $ig = $getChannel('instagram'); @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 platform-card">
                    <div class="card-header platform-header bg-instagram d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fab fa-instagram mr-2"></i> Instagram</h4>
                        @if($ig && $ig->is_active)<span class="status-badge"><i class="fas fa-check-circle mr-1"></i> Connected</span>@endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('omni-chat.settings.update') }}" method="POST" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="platform" value="instagram">
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Account Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. @acmecorp" value="{{ $ig->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Business Account ID</label>
                                <input type="text" name="identifier" class="form-control" placeholder="IG Account ID" value="{{ $ig->identifier ?? '' }}" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-muted font-weight-bold small">Access Token</label>
                                <input type="password" name="api_key" class="form-control" placeholder="EAA..." value="{{ $ig->credentials['api_key'] ?? '' }}" required>
                            </div>
                            <div class="mt-auto">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="ig_active" value="1" {{ ($ig && $ig->is_active) || !$ig ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="ig_active">Enable this channel</label>
                                </div>
                                <button type="submit" class="btn btn-danger btn-save" style="background: linear-gradient(45deg, #e6683c, #dc2743); border: none;">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TikTok -->
            @php $tt = $getChannel('tiktok'); @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 platform-card">
                    <div class="card-header platform-header bg-tiktok d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fab fa-tiktok mr-2"></i> TikTok</h4>
                        @if($tt && $tt->is_active)<span class="status-badge"><i class="fas fa-check-circle mr-1"></i> Connected</span>@endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('omni-chat.settings.update') }}" method="POST" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="platform" value="tiktok">
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Profile Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. AcmeCorp Official" value="{{ $tt->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">TikTok App ID</label>
                                <input type="text" name="identifier" class="form-control" placeholder="App ID" value="{{ $tt->identifier ?? '' }}" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-muted font-weight-bold small">Client Secret</label>
                                <input type="password" name="secret" class="form-control" placeholder="Client Secret" value="{{ $tt->credentials['secret'] ?? '' }}" required>
                            </div>
                            <div class="mt-auto">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="tt_active" value="1" {{ ($tt && $tt->is_active) || !$tt ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="tt_active">Enable this channel</label>
                                </div>
                                <button type="submit" class="btn btn-dark btn-save">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- LinkedIn -->
            @php $li = $getChannel('linkedin'); @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 platform-card">
                    <div class="card-header platform-header bg-linkedin d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fab fa-linkedin mr-2"></i> LinkedIn</h4>
                        @if($li && $li->is_active)<span class="status-badge"><i class="fas fa-check-circle mr-1"></i> Connected</span>@endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('omni-chat.settings.update') }}" method="POST" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="platform" value="linkedin">
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Company Page Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Acme Corp" value="{{ $li->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Organization ID</label>
                                <input type="text" name="identifier" class="form-control" placeholder="LinkedIn Organization URN" value="{{ $li->identifier ?? '' }}" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-muted font-weight-bold small">Access Token</label>
                                <input type="password" name="api_key" class="form-control" placeholder="OAuth 2.0 Access Token" value="{{ $li->credentials['api_key'] ?? '' }}" required>
                            </div>
                            <div class="mt-auto">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="li_active" value="1" {{ ($li && $li->is_active) || !$li ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="li_active">Enable this channel</label>
                                </div>
                                <button type="submit" class="btn btn-primary btn-save" style="background-color: #0A66C2; border: none;">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mail -->
            @php $mail = $getChannel('mail'); @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 platform-card">
                    <div class="card-header platform-header bg-mail d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fas fa-envelope mr-2"></i> Email</h4>
                        @if($mail && $mail->is_active)<span class="status-badge"><i class="fas fa-check-circle mr-1"></i> Connected</span>@endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('omni-chat.settings.update') }}" method="POST" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="platform" value="mail">
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Inbox Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Support Inbox" value="{{ $mail->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Email Address</label>
                                <input type="email" name="identifier" class="form-control" placeholder="support@domain.com" value="{{ $mail->identifier ?? '' }}" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-muted font-weight-bold small">App Password</label>
                                <input type="password" name="api_key" class="form-control" placeholder="Password or OAuth Token" value="{{ $mail->credentials['api_key'] ?? '' }}" required>
                            </div>
                            <div class="mt-auto">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="mail_active" value="1" {{ ($mail && $mail->is_active) || !$mail ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="mail_active">Enable this channel</label>
                                </div>
                                <button type="submit" class="btn btn-warning btn-save text-white">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- WhatsApp (API) -->
            @php $wa_api = $getChannel('whatsapp_api'); @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 platform-card">
                    <div class="card-header platform-header bg-whatsapp d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fab fa-whatsapp mr-2"></i> WhatsApp API</h4>
                        @if($wa_api && $wa_api->is_active)<span class="status-badge"><i class="fas fa-check-circle mr-1"></i> Connected</span>@endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('omni-chat.settings.update') }}" method="POST" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="platform" value="whatsapp_api">
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Account Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Official WA Business" value="{{ $wa_api->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Phone Number ID</label>
                                <input type="text" name="identifier" class="form-control" placeholder="WA Phone Number ID" value="{{ $wa_api->identifier ?? '' }}" required>
                            </div>
                            <div class="form-group mb-4">
                                <label class="text-muted font-weight-bold small">Permanent Access Token</label>
                                <input type="password" name="api_key" class="form-control" placeholder="EA... token" value="{{ $wa_api->credentials['api_key'] ?? '' }}" required>
                            </div>
                            <div class="mt-auto">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="wa_api_active" value="1" {{ ($wa_api && $wa_api->is_active) || !$wa_api ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="wa_api_active">Enable this channel</label>
                                </div>
                                <button type="submit" class="btn btn-success btn-save" style="background-color: #25D366; border: none;">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- WhatsApp (QR) -->
            @php $wa_qr = $getChannel('whatsapp_qr'); @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 platform-card">
                    <div class="card-header platform-header bg-whatsapp d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fab fa-whatsapp mr-2"></i> WhatsApp QR</h4>
                        @if($wa_qr && $wa_qr->is_active)<span class="status-badge"><i class="fas fa-check-circle mr-1"></i> Connected</span>@endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('omni-chat.settings.update') }}" method="POST" class="flex-grow-1 d-flex flex-column">
                            @csrf
                            <input type="hidden" name="platform" value="whatsapp_qr">
                            
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Session Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Support Phone 1" value="{{ $wa_qr->name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted font-weight-bold small">Phone Number</label>
                                <input type="text" name="identifier" class="form-control" placeholder="+1234567890" value="{{ $wa_qr->identifier ?? '' }}" required>
                            </div>
                            
                            <div class="text-center my-3">
                                <div class="border rounded p-3 d-inline-block bg-light shadow-sm" style="border-radius: 10px !important;">
                                    <i class="fas fa-qrcode text-secondary" style="font-size: 50px;"></i>
                                    <p class="mt-2 mb-0 small text-muted font-weight-bold">Ready to Scan</p>
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="custom-control custom-switch mb-3">
                                    <input type="checkbox" name="is_active" class="custom-control-input" id="wa_qr_active" value="1" {{ ($wa_qr && $wa_qr->is_active) || !$wa_qr ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="wa_qr_active">Enable this channel</label>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary font-weight-bold w-50" style="border-radius: 8px;">Generate</button>
                                    <button type="submit" class="btn btn-success btn-save w-50 ml-2" style="background-color: #128C7E; border: none;">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </x-slot>
</x-layouts.admin>
