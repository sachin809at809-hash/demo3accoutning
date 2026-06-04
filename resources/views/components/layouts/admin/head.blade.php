@props([
    'metaTitle', 'title', 'currency'
])

<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>{!! ! empty($metaTitle) ? $metaTitle : $title !!} - @setting('company.name')</title>

    <base href="{{ config('app.url') . '/' }}">

    <x-layouts.pwa.head />

    <link rel="stylesheet" href="{{ asset('public/css/custom_loading.css?v=' . version('short')) }}" type="text/css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/png">

    <!-- Obsidian Prism Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', sans-serif; }
        .mono-data { font-family: 'JetBrains Mono', monospace; }
        .text-obsidian-surface { color: #dae2fd; }
        .bg-obsidian-glass { background-color: rgba(19, 27, 46, 0.7); backdrop-filter: blur(12px); border-color: #464554; }
        .gradient-primary { background: linear-gradient(to right, #6366f1, #a855f7); color: white; }
    </style>

    <!--Icons-->
    <link rel="stylesheet" href="{{ asset('public/css/fonts/material-icons/style.css?v=' . version('short')) }}" type="text/css">

     <!-- Font -->
    <link rel="stylesheet" href="{{ asset('public/vendor/quicksand/css/quicksand.css?v=' . version('short')) }}" type="text/css">

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('public/css//third_party/swiper-bundle.min.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css//third_party/vue-html-editor.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/element.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/app.css?v=' . version('short')) }}" type="text/css">
    
    <!-- Custom Blanxer ERP Theme -->
    <link rel="stylesheet" href="{{ asset('public/css/blanxer.css?v=' . version('short')) }}" type="text/css">

    @stack('css')

    @stack('stylesheet')

    @livewireStyles

    <script type="text/javascript"><!--
        var url = '{{ url("/" . company_id()) }}';
        var app_url = '{{ config("app.url") }}';
        var aka_currency = {!! !empty($currency) ? json_encode($currency) : 'false' !!};
        var all_currencies = {!! !empty($currencies) ? json_encode($currencies) : '[]' !!};
    //--></script>

    <x-script.exceptions.trackers />

    @stack('js')

    <script type="text/javascript"><!--
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;

        var flash_notification = {!! (session()->has('flash_notification')) ? json_encode(session()->get('flash_notification')) : 'false' !!};
    //--></script>

    {{ session()->forget('flash_notification') }}

    @stack('scripts')

    @stack('head_end')
</head>
