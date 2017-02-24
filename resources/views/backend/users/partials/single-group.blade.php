<tr class="row">
    <td class="col-md-1">
        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}">
            <i class="fa fa-edit"></i>
        </button>
    </td>
    <td class="col-md-2">
        <p>
            <strong>{{ $inscription->inscription_no }}</strong>&nbsp;
            @if($inscription->occurrences->isEmpty())
                @include('backend.partials.toggle', ['id' => $inscription->id])
            @endif
        </p>

        @if(!$inscription->occurrences->isEmpty())
            @foreach($inscription->occurrences as $occurrence)
                <small style="display: block;">
                    Le {{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}&nbsp;
                    @include('backend.partials.toggle', ['id' => $inscription->id, 'inscription' => $occurrence->pivot])
                </small>
            @endforeach
        @endif
    </td>
    <td class="col-md-2"><p><strong>{{ $inscription->participant->name }}</strong></p></td>
    <td class="col-md-2">{{ $inscription->price_cents }} CHF</td>
    <td class="col-md-1">
        @if($inscription->doc_bon)
            <a target="_blank" href="{{ secure_asset($inscription->doc_bon) }}{{ '?'.rand(0,1000) }}" class="btn btn-default btn-sm"><i class="fa fa-file"></i> &nbsp;Bon</a>
        @endif
    </td>
    <td class="col-md-4">
        <!-- Occurences -->
        @if(!$inscription->occurrences->isEmpty())
            <h4>Conférences</h4>
            <ol>
                @foreach($inscription->occurrences as $occurrences)
                    <li>{{ $occurrences->title }}</li>
                @endforeach
            </ol>
        @endif

        @include('backend.users.inscription.options')
    </td>
</tr>