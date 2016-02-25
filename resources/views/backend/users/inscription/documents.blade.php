@if(!empty($inscription->documents))
    <div class="btn-group">
        @foreach($inscription->documents as $type => $annexe)
            <a target="_blank" href="{{ asset($annexe['link']) }}" class="btn btn-default btn-sm">{{ strtoupper($type) }}</a>
        @endforeach
    </div>
@endif

@if(!$inscription->rappels->isEmpty())
    <p>
        <ol>
            @foreach($inscription->rappels as $rappel)
                <li>
                    <a target="_blank" href="{{ asset($rappel->doc_rappel) }}" class="btn btn-default btn-sm">Rappel {{ $rappel->created_at->format('d/m/Y') }}</a>
                </li>
            @endforeach
        </ol>
    </p>
@endif