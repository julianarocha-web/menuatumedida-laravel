@if ($paginator->hasPages())
    <nav class="site-pagination" aria-label="Páginas de resultados">
        @if ($paginator->onFirstPage())
            <span class="btn btn-outline-secondary disabled" aria-disabled="true">Anterior</span>
        @else
            <a class="btn btn-outline-secondary" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a>
        @endif
        <span>Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>
        @if ($paginator->hasMorePages())
            <a class="btn btn-outline-secondary" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a>
        @else
            <span class="btn btn-outline-secondary disabled" aria-disabled="true">Siguiente</span>
        @endif
    </nav>
@endif