<div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'mb-14 p-8 bg-obsidian-glass shadow-2xl rounded-3xl transition-all duration-300 hover:shadow-purple-500/10']) : $attributes }}>
    @if (!empty($head) && $head->isNotEmpty())
        {!! $head !!}
    @endif

    @if (! empty($body) && $body->isNotEmpty())
        <div
            @class([
                'grid my-3.5',
                $spacingVertical,
                $spacingHorizontal,
                $columnNumber,
            ])
        >
            {!! $body !!}
        </div>
    @endif

    @if (! empty($foot) && $foot->isNotEmpty())
        {!! $foot !!}
    @endif
</div>
