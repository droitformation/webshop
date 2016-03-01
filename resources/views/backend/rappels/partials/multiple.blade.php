<tr>
    <td>
        <form action="{{ url('admin/inscription/rappel') }}" method="POST">{!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $id }}">
            <button class="btn btn-warning btn-sm">Générer un rappel</button>
        </form>
    </td>
    <td>
        <p><strong>{{ ($group->user && $group->user->adresse_facturation ? $group->user->adresse_facturation->civilite_title : '') }}</strong></p>

        @if($group->user)
            <p><a href="{{ url('admin/user/'.$group->user->id) }}">{{ $group->user->name }}</a></p>
        @else
            <p><span class="label label-warning">Duplicata</span></p>
        @endif
    </td>
    <td>
        @if($group->inscriptions)
            <dl>
                @foreach($group->inscriptions as $inscription)
                    <dt>{!! $inscription->participant->name !!}</dt>
                    <dd>{{ $inscription->inscription_no }}</dd>
                @endforeach
            </dl>
        @endif
    </td>
    <td>{{ $group->price }} CHF</td>
    <td> @include('backend.inscriptions.partials.payed', ['inscription' => $group->inscriptions->first(), 'model' => 'group', 'item' => $group]) </td>
    <td>{{ $group->inscriptions->first()->created_at->formatLocalized('%d %B %Y') }}</td>
    <td>
        @if(!$group->rappels->isEmpty())
            <ol>
                @foreach($group->rappels as $rappel)
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