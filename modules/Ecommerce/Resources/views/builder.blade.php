<x-layouts.admin>
    <x-slot name="title">
        Website Builder ({{ $page->title }})
    </x-slot>

    @push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/css/grapes.min.css">
        <style>
            /* Make GrapesJS take up the full screen height available */
            #gjs {
                border: 1px solid #ddd;
                border-radius: 4px;
                height: 80vh !important;
            }
        </style>
    @endpush

    <x-slot name="content">
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between">
                <h4>Editing: {{ $page->title }}</h4>
                <div>
                    <a href="{{ route('ecommerce.store.index') }}" target="_blank" class="btn btn-secondary">View Live Store</a>
                    <button class="btn btn-primary" id="save-btn">Save Changes</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- GrapesJS Editor Container -->
                <div id="gjs"></div>
            </div>
        </div>
    </x-slot>

    @push('scripts_start')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.2/grapes.min.js"></script>
        <!-- Use the Basic Blocks plugin which is fully compatible -->
        <script src="https://unpkg.com/grapesjs-blocks-basic"></script>
        
        <script>
            // Initialize GrapesJS
            const editor = grapesjs.init({
                container: '#gjs',
                fromElement: true,
                height: '100%',
                width: 'auto',
                storageManager: {
                    type: 'remote',
                    stepsBeforeSave: 1,
                    autosave: false, // We use a manual save button
                    urlStore: '{{ route("ecommerce.builder.save", $page->id) }}',
                    urlLoad: '{{ route("ecommerce.builder.load", $page->id) }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                plugins: ['gjs-blocks-basic'],
                pluginsOpts: {
                    'gjs-blocks-basic': {
                        flexGrid: true,
                        blocks: ['column1', 'column2', 'column3', 'column3-7', 'text', 'link', 'image', 'video', 'map']
                    }
                }
            });

            // Handle manual save
            document.getElementById('save-btn').addEventListener('click', function() {
                editor.store(res => {
                    alert('Page saved successfully!');
                });
            });
        </script>
    @endpush
</x-layouts.admin>
