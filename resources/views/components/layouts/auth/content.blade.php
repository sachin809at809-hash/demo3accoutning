@stack('content_start')
<div class="w-full lg:w-46 lg:my-12 h-auto min-h-[31rem] flex flex-col justify-center gap-12 px-6 lg:px-16 py-16 mt-12 lg:mt-0 glass-card mx-4 lg:mx-0">
    <div class="flex flex-col gap-4">
        {!! $slot !!}
    </div>
</div>
@stack('content_end')
