<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.auth.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.auth.head>

    @mobile
    <body class="bg-body">
    @elsemobile
    <body class="bg-body overflow-y-overlay">
    @endmobile

        @stack('body_start')

        <!-- Custom Glassmorphism Styles -->
        <style>
            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                border-radius: 16px;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .dark .glass-card {
                background: rgba(30, 30, 40, 0.7);
                border: 1px solid rgba(255, 255, 255, 0.05);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
            }
            .gradient-text {
                background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .gradient-btn {
                background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
                color: #fff !important;
                border: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.3);
            }
            .gradient-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.4);
                opacity: 0.95;
            }
        </style>

        <div id="app" class="h-screen lg:h-auto bg-no-repeat bg-cover bg-center" style="background-image: url({{ asset('public/img/auth/login-bg.png') }});">
            <div class="relative w-full lg:max-w-7xl flex items-center m-auto">
                <x-layouts.auth.slider>
                    {!! $slider ?? '' !!}
                </x-layouts.auth.slider>

                <x-layouts.auth.content>
                    {!! $content !!}

                    <x-layouts.auth.footer />
                </x-layouts.auth.content>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.auth.scripts />
    </body>
</html>
