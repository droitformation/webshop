<?php setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>
<dl>
    @if($product->pages)
        <dt>Pages</dt>
        <dd>{{ $product->pages }}</dd>
    @endif
    @if($product->reliure)
        <dt>Reliure</dt>
        <dd>{{ $product->reliure }}</dd>
    @endif
    @if($product->format)
        <dt>Format</dt>
        <dd>{{ $product->format }}</dd>
    @endif
    @if($product->edition_at)
        <dt>Date d'édition</dt>
        <dd>{{ $product->edition_at ? $product->edition_at->formatLocalized('%d %B %Y') : '' }}</dd>
    @endif
</dl>