<x-layouts.admin>
    <x-slot name="title">
        Integrations & Settings
    </x-slot>

    <x-slot name="content">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-white"><i class="fas fa-sync-alt mr-2"></i> WooCommerce Integration</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">
                            Connect your existing WooCommerce store to automatically import your products, prices, and inventory into the ERP.
                        </p>

                        <form action="{{ route('ecommerce.settings.update') }}" method="POST">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="woocommerce_url" class="font-weight-bold">WooCommerce Store URL</label>
                                <input type="url" name="woocommerce_url" id="woocommerce_url" class="form-control" placeholder="https://yourstore.com" value="{{ setting('ecommerce.woocommerce.url') }}">
                                <small class="form-text text-muted">The full URL of your WordPress installation.</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="woocommerce_consumer_key" class="font-weight-bold">Consumer Key</label>
                                    <input type="text" name="woocommerce_consumer_key" id="woocommerce_consumer_key" class="form-control" value="{{ setting('ecommerce.woocommerce.consumer_key') }}">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="woocommerce_consumer_secret" class="font-weight-bold">Consumer Secret</label>
                                    <input type="password" name="woocommerce_consumer_secret" id="woocommerce_consumer_secret" class="form-control" value="{{ setting('ecommerce.woocommerce.consumer_secret') }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success mt-2">Save Credentials</button>
                        </form>

                        <hr class="my-5">

                        <h5 class="mb-3">Manual Sync</h5>
                        <p class="text-muted text-sm">Once credentials are saved, click the button below to download the latest products from your WooCommerce store.</p>
                        
                        <form action="{{ route('ecommerce.settings.sync.woocommerce') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100" {{ !setting('ecommerce.woocommerce.url') ? 'disabled' : '' }}>
                                <i class="fas fa-cloud-download-alt mr-2"></i> Run WooCommerce Sync
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-info-circle text-info mr-1"></i> How to get API Keys</h5>
                        <ol class="pl-3 mt-3 text-sm">
                            <li class="mb-2">Log into your WordPress Admin Dashboard.</li>
                            <li class="mb-2">Go to <strong>WooCommerce > Settings</strong>.</li>
                            <li class="mb-2">Click the <strong>Advanced</strong> tab, then click <strong>REST API</strong>.</li>
                            <li class="mb-2">Click <strong>Add Key</strong>.</li>
                            <li class="mb-2">Add a description, set permissions to <strong>Read</strong>, and generate.</li>
                            <li class="mb-2">Copy the Key and Secret into this form.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.admin>
