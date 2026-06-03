<x-layouts.admin>
    <x-slot name="title">
        Quick-Commerce Delivery Zones
    </x-slot>

    <x-slot name="stylesheet">
        <style>
            #map {
                height: 500px;
                width: 100%;
                border-radius: 8px;
                border: 1px solid #ddd;
            }
        </style>
    </x-slot>

    <x-slot name="content">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Draw Delivery Zone</h4>
                    </div>
                    <div class="card-body">
                        <!-- Instructions -->
                        <p class="text-muted mb-3">
                            Click the polygon icon on the map tools to draw a new delivery zone. Double click to complete the shape.
                        </p>
                        
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Save Zone Pricing</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ecommerce.zones.store') }}" method="POST">
                            @csrf
                            
                            <!-- Hidden input to store polygon string -->
                            <input type="hidden" name="polygon_data" id="polygon_data" required>
                            
                            <div class="form-group mb-3">
                                <label>Zone Name</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Downtown Core" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Delivery Fee ($)</label>
                                <input type="number" step="0.01" name="delivery_fee" class="form-control" placeholder="5.00" required>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label>Estimated Minutes (Optional)</label>
                                <input type="number" name="estimated_minutes" class="form-control" placeholder="e.g. 15">
                                <small class="text-muted">For 10-minute Quick Commerce guarantees.</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100" id="save-zone-btn" disabled>
                                Save Delivery Zone
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Existing Zones List -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Active Zones</h4>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse($zones as $zone)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $zone->name }}
                                        <span class="badge badge-success ml-2">@money($zone->delivery_fee, setting('default.currency'), true)</span>
                                    </div>
                                    <form action="{{ route('ecommerce.zones.destroy', $zone->id) }}" method="POST" onsubmit="return confirm('Delete this zone?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">No zones defined yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="script">
        <!-- Load Google Maps JS API with Drawing Library (Requires API Key in production) -->
        <!-- Note: Replace YOUR_API_KEY with actual Google Maps API key in production env -->
        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing"></script>
        
        <script>
            let map;
            let drawingManager;
            let currentPolygon = null;

            function initMap() {
                // Default center (San Francisco for example)
                map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: 37.7749, lng: -122.4194 },
                    zoom: 12
                });

                drawingManager = new google.maps.drawing.DrawingManager({
                    drawingMode: google.maps.drawing.OverlayType.POLYGON,
                    drawingControl: true,
                    drawingControlOptions: {
                        position: google.maps.ControlPosition.TOP_CENTER,
                        drawingModes: [
                            google.maps.drawing.OverlayType.POLYGON
                        ]
                    },
                    polygonOptions: {
                        fillColor: '#FF0000',
                        fillOpacity: 0.35,
                        strokeWeight: 2,
                        clickable: false,
                        editable: true,
                        zIndex: 1
                    }
                });

                drawingManager.setMap(map);

                google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
                    // Remove previous polygon if drawn
                    if (currentPolygon) {
                        currentPolygon.setMap(null);
                    }
                    
                    currentPolygon = polygon;
                    
                    // Switch back to hand/drag mode
                    drawingManager.setDrawingMode(null);
                    
                    extractPolygonData(polygon);
                    
                    // Add listener to update coordinates if user drags points
                    polygon.getPaths().forEach(function(path, index) {
                        google.maps.event.addListener(path, 'insert_at', function() { extractPolygonData(polygon); });
                        google.maps.event.addListener(path, 'remove_at', function() { extractPolygonData(polygon); });
                        google.maps.event.addListener(path, 'set_at', function() { extractPolygonData(polygon); });
                    });
                });
            }

            function extractPolygonData(polygon) {
                let vertices = polygon.getPath();
                let coordinates = [];

                for (let i = 0; i < vertices.getLength(); i++) {
                    let xy = vertices.getAt(i);
                    coordinates.push({ lat: xy.lat(), lng: xy.lng() });
                }

                // Update hidden input
                document.getElementById('polygon_data').value = JSON.stringify(coordinates);
                
                // Enable submit button
                document.getElementById('save-zone-btn').disabled = false;
            }

            // Init on load
            google.maps.event.addDomListener(window, 'load', initMap);
        </script>
    </x-slot>
</x-layouts.admin>
