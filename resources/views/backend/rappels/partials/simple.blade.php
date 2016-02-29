<tr {!! ($inscription->group_id ? 'class="isGoupe"' : '') !!}>
    <td>
        <form action="{{ url('admin/inscription/rappel') }}" method="POST">{!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $inscription->id }}">
            <button class="btn btn-warning btn-sm">Générer un rappel</button>
        </form>
    </td>
    <td>
        <p><strong>{{ ($inscription->inscrit && $inscription->adresse_facturation ? $inscription->adresse_facturation->civilite_title : '') }}</strong></p>

        @if($inscription->inscrit)
            <p><a href="{{ url('admin/user/'.$inscription->inscrit->id) }}">{{ $inscription->inscrit->name }}</a></p>
        @else
            <p><span class="label label-warning">Duplicata</span></p>
        @endif

    </td>
    <td><strong>{{ $inscription->inscription_no }}</strong></td>
    <td>{{ $inscription->price->price_cents }} CHF</td>
    <td> @include('backend.inscriptions.partials.payed',['model' => 'inscription', 'item' => $inscription]) </td>
    <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
    <td>
        @if(!$inscription->rappels->isEmpty())
            <ol>
                @foreach($inscription->rappels as $rappel)
                    <li>
                        <form action="{{ url('admin/inscription/rappel/'.$rappel->id) }}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                            <a target="_blank" href="{{ asset($rappel->doc_rappel) }}">Rappel {{ $rappel->created_at->format('d/m/Y') }}</a> &nbsp;
                            <button data-what="Supprimer" data-action="le rappel" class="btn btn-danger btn-sm deleteAction pull-right"><i class="fa fa-times"></i></button>
                        </form>
                    </li>
                @endforeach
            </ol>
        @endif
    </td>
</tr>