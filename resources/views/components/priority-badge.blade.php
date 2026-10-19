@props([
    'priority',
    'label' => null,
])

@php
    $labels = [
        'low' => 'Baixa',
        'medium' => 'Média',
        'high' => 'Alta',
    ];

    $classes = [
        'low' => 'badge-neutral',
        'medium' => 'badge-em_andamento',
        'high' => 'badge-cancelado',
    ];

    $text = $label ?? ($labels[$priority] ?? $priority);
    $class = $classes[$priority] ?? 'badge-neutral';
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70" aria-hidden="true"></span>
    {{ $text }}
</span>
