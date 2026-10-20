@php
    $pageName = $paginator->getPageName();

    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginação" class="flex flex-col items-center gap-4 xl:flex-row xl:justify-between">
        <p class="hidden text-sm text-slate-500 xl:block">
            Mostrando
            @if ($paginator->firstItem())
                <span class="font-medium text-slate-700">{{ $paginator->firstItem() }}</span>
                a
                <span class="font-medium text-slate-700">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            de
            <span class="font-medium text-slate-700">{{ $paginator->total() }}</span>
            {{ $paginator->total() === 1 ? 'resultado' : 'resultados' }}
        </p>

        <div class="flex items-center gap-1.5">
            {{-- Página anterior --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="Página anterior" class="inline-flex cursor-not-allowed items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-400 opacity-70">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="hidden lg:inline">Anterior</span>
                </span>
            @else
                <button type="button"
                        wire:click="previousPage('{{ $pageName }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        aria-label="Página anterior"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition-colors hover:border-indigo-200 hover:bg-indigo-50/60 hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="hidden lg:inline">Anterior</span>
                </button>
            @endif

            {{-- Páginas numeradas --}}
            <div class="hidden items-center gap-1 md:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span aria-hidden="true" class="inline-flex cursor-default items-center justify-center rounded-lg px-2 py-2 text-sm font-medium text-slate-400">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span wire:key="paginator-{{ $pageName }}-page-{{ $page }}"
                                      aria-current="page"
                                      class="inline-flex min-w-10 items-center justify-center rounded-lg bg-gradient-to-r from-blue-700 via-indigo-600 to-violet-600 px-3 py-2 text-sm font-semibold text-white shadow-sm shadow-indigo-600/25">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button"
                                        wire:key="paginator-{{ $pageName }}-page-{{ $page }}"
                                        wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        wire:loading.attr="disabled"
                                        aria-label="Ir para a página {{ $page }}"
                                        class="inline-flex min-w-10 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition-colors hover:border-indigo-200 hover:bg-indigo-50/60 hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Indicador compacto em telas pequenas --}}
            <span class="text-sm text-slate-500 md:hidden">
                Página <span class="font-medium text-slate-700">{{ $paginator->currentPage() }}</span> de <span class="font-medium text-slate-700">{{ $paginator->lastPage() }}</span>
            </span>

            {{-- Próxima página --}}
            @if ($paginator->hasMorePages())
                <button type="button"
                        wire:click="nextPage('{{ $pageName }}')"
                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                        wire:loading.attr="disabled"
                        aria-label="Próxima página"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition-colors hover:border-indigo-200 hover:bg-indigo-50/60 hover:text-indigo-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">
                    <span class="hidden lg:inline">Próxima</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @else
                <span aria-disabled="true" aria-label="Próxima página" class="inline-flex cursor-not-allowed items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-400 opacity-70">
                    <span class="hidden lg:inline">Próxima</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
