<x-loading.content />

<div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'my-5 bg-obsidian-glass shadow-2xl rounded-3xl overflow-hidden']) : $attributes }}>
    {!! $slot !!}
</div>
