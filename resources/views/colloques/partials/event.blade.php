<div class="grid-item">
    <div class="grid-item-content" data-colloque="{{ $colloque->id }}">
        <div class="item-bloc">
            <header>
                <span style="width:60px;height: 60px; background: #ddd; float: left; margin-right: 10px;">
                     <?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>
                    {{ $colloque->start_at->formatLocalized('%B') }}
                    {{ $colloque->start_at->format('d') }}
                    {{ $colloque->start_at->format('Y') }}
                </span>
                <p>{{ $colloque->titre }}<br/>{{ $colloque->soustitre }}</p>
            </header>
            <div class="body">
                <address>
                    <p><strong>Lieu:</strong></p>
                    <p>{{ $colloque->location->name }}, {{ $colloque->location->adresse }}</p>
                </address>
            </div>
            <footer>
                @if(isset($colloque->centres))
                    @foreach($colloque->centres as $center)
                        <a href="{{ $center->url }}">
                            <img style="max-width: 45%; max-height: 65px;" src="{{ asset('files/logos/'.$center->logo) }}">
                        </a>
                    @endforeach
                @endif
            </footer>
        </div>
    </div>
</div>