<tr>
    <td>
        <a class="btn btn-sky btn-sm" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}"><i class="fa fa-edit"></i></a>
        @include('backend.users.modals.edit', ['inscription' => $inscription])
        <a class="btn btn-sky btn-sm" data-toggle="modal" data-target="#singleInscription_{{ $inscription->id }}"><i class="fa fa-eye"></i></a>
        @include('backend.users.modals.inscription', ['inscription' => $inscription])
    </td>
    <td>
        <p><strong>{{ $inscription->detenteur['civilite'] }}</strong></p>
        <p><a href="{{ url('admin/user/'.$inscription->detenteur['id']).'?path' }}">{{ $inscription->detenteur['name'] }}</a></p>
        <p>{{ $inscription->detenteur['email'] }}</p>
    </td>
    <td>
        <strong>{{ $inscription->inscription_no }}</strong>&nbsp;
        @include('backend.partials.toggle', ['inscription' => $inscription, 'id' => $inscription->id])
    </td>
    <td>{{ $inscription->price_cents }} CHF</td>
    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
    <td>@include('backend.inscriptions.partials.payed',['model' => 'inscription', 'item' => $inscription, 'inscription' => $inscription])</td>
    <td class="text-right">
        <form action="{{ url('admin/inscription/'. $inscription->id) }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
            <input type="hidden" name="_method" value="DELETE">
            <button data-what="Supprimer" data-action="l'inscription" class="btn btn-danger btn-sm deleteAction">X</button>
        </form>
    </td>
</tr>