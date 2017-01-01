<tr class="isGoupe">
    <td></td>
    <td>
        <p><strong>{{ $inscription->detenteur['civilite'] }}</strong></p>
        <p><a href="{{ url('admin/user/'.$inscription->detenteur['id']) }}">{{ $inscription->detenteur['name'] }}</a></p>
        <p>{{ $inscription->detenteur['email'] }}</p>

        <p><a class="btn btn-info btn-xs btn-isGroupe" data-toggle="modal" data-target="#editGroup_{{ $inscription->groupe->id }}">Changer le détenteur</a></p>
        @include('backend.inscriptions.modals.change', ['group' => $inscription->groupe, 'adresse' => $inscription->adresse_inscrit ]) <!-- Modal edit group -->
    </td>
    <td>

        @if($inscription->groupe->inscriptions)
            @foreach($inscription->groupe->inscriptions as $participant)
                @include('backend.inscriptions.partials.participant', ['inscription' => $participant])
            @endforeach
        @endif

        <p><a class="btn btn-success btn-xs btn-isGroupe" data-toggle="modal" data-target="#addToGroup_{{ $inscription->groupe->id }}">Ajouter un participant</a></p>
        @include('backend.inscriptions.modals.add', ['group' => $inscription->groupe, 'colloque' => $colloque, 'inscription' => null]) <!-- Modal add to group -->

    </td>
    <td>{{ $inscription->groupe->price_cents }} CHF</td>
    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>@include('backend.inscriptions.partials.payed',['model' => 'group', 'item' => $inscription->groupe, 'inscription' => $inscription])</td>
    <td class="text-right">
        <form action="{{ url('admin/group/'. $inscription->groupe->id) }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
            <input type="hidden" name="_method" value="DELETE">
            <button data-what="Supprimer" data-action="le groupe et ses inscriptions" class="btn btn-danger btn-sm deleteAction">X</button>
        </form>
    </td>
</tr>