<div class="grid-item">
    <div class="grid-item-content">
        <div class="item-bloc">
            <header>
                                <span style="width:60px;height: 60px; background: #ddd; float: left; margin-right: 10px;">
                                     <?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>
                                    {{ $item->start_at->formatLocalized('%B') }}
                                    {{ $item->start_at->format('d') }}
                                    {{ $item->start_at->format('Y') }}
                                </span>
                <p>{{ $item->titre }}<br/>{{ $item->soustitre }}</p>
            </header>
            <div class="body">
                <address>
                    <p><strong>Lieu:</strong></p>
                    <p>{{ $item->location->name }}, {{ $item->location->adresse }}</p>
                </address>
            </div>
            <footer>
                @if(isset($item->centres))
                    @foreach($item->centres as $center)
                        <a href="{{ $center->url }}">
                            <img style="max-width: 45%; max-height: 65px;" src="{{ asset('files/logos/'.$center->logo) }}">
                        </a>
                    @endforeach
                @endif
            </footer>
        </div>
    </div>
</div>