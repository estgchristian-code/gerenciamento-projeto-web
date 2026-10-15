@props([
    'status',
    'label' => null,
])

@php
    $labels = [
        'planejamento' => 'Planejamento',
        'em_andamento' => 'Em andamento',
        'concluido' => 'Concluído',
        'cancelado' => 'Cancelado',
        'todo' => 'A fazer',
        'in_progress' => 'Em progresso',
        'done' => 'Concluída',
        'blocked' => 'Bloqueada',
    ];

    $classes = [
        'planejamento' => 'badge-planejamento',
        'em_andamento' => 'badge-em_andamento',
        'concluido' => 'badge-concluido',
        'cancelado' => 'badge-cancelado',
        'todo' => 'badge-neutral',
        'in_progress' => 'badge-planejamento',
        'done' => 'badge-concluido',
        'blocked' => 'badge-cancelado',
    ];

    $text = $label ?? ($labels[$status] ?? $status);
    $class = $classes[$status] ?? 'badge-neutral';
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70" aria-hidden="true"></span>
    {{ $text }}
</span>
