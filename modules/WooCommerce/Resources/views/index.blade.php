<x-layouts.admin>
    <x-slot name="title">
        WooCommerce Settings
    </x-slot>

    <x-slot name="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">API Credentials</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('woo-commerce.settings.update') }}" method="POST">
                            @csrf
                            
                            <div class="form-group row">
                                <label for="store_url" class="col-md-3 col-form-label">Store URL</label>
                                <div class="col-md-9">
                                    <input type="url" name="store_url" id="store_url" class="form-control" value="{{ setting('woo-commerce.store_url') }}" placeholder="https://your-store.com">
                                </div>
                            </div>
                            
                            <div class="form-group row mt-3">
                                <label for="consumer_key" class="col-md-3 col-form-label">Consumer Key (ck_...)</label>
                                <div class="col-md-9">
                                    <input type="text" name="consumer_key" id="consumer_key" class="form-control" value="{{ setting('woo-commerce.consumer_key') }}">
                                </div>
                            </div>

                            <div class="form-group row mt-3">
                                <label for="consumer_secret" class="col-md-3 col-form-label">Consumer Secret (cs_...)</label>
                                <div class="col-md-9">
                                    <input type="text" name="consumer_secret" id="consumer_secret" class="form-control" value="{{ setting('woo-commerce.consumer_secret') }}">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">Save Settings</button>
                                <a href="{{ route('woo-commerce.sync') }}" class="btn btn-primary ml-2">Manual Sync Orders</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-layouts.admin>
