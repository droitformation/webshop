<div class="grid-item">
    <div class="grid-item-content" id="colloque_{{ $colloque->id }}" data-colloque="{{ $colloque->id }}">
        <div class="item-bloc">
            <div class="closing">x</div>
            <header>
                <div class="colloque_date">
                    <?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>
                    <span class="month">{{ $colloque->start_at->formatLocalized('%b') }}</span>
                    <span class="day">  {{ $colloque->start_at->format('d') }}</span>
                    <span class="year"> {{ $colloque->start_at->format('Y') }}</span>
                </div>
                <p><strong>{{ $colloque->titre }}</strong><br/>{{ $colloque->soustitre }}</p>

                @if($colloque->registration_at >= date('Y-m-d'))
                    <a href="{{ url('colloque/inscription/'.$colloque->id) }}" class="btn btn-danger btn-sm pull-right">inscription</a>
                @endif

            </header>
            <div class="body">
                <address>
                    <p><strong>Lieu:</strong></p>
                    <p>{{ $colloque->location ? $colloque->location->name : '' }}, {{ $colloque->location ? $colloque->location->adresse : '' }}</p>
                </address>
                <div class="inner"></div>
            </div>
            <footer>
                @if(isset($colloque->centres))
                    @foreach($colloque->centres as $center)
                        <a href="{{ $center->url }}">
                            <img style="max-width: 45%; max-height: 55px;" src="{{ asset('files/logos/'.$center->logo) }}">
                        </a>
                    @endforeach
                @endif
            </footer>
        </div>
    </div>
</div>