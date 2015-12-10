<form action="{{ url('admin/facture/'.$payement->id) }}" method="POST">
    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
    <a class="btn btn-sm btn-default" href="{{ asset('files/abos/'.$type.'_'.$facture->abo_facture) }}"><i class="fa fa-file"></i> &nbsp;{{ $type }} pdf</a>
    <a href="{{ url('admin/facture') }}" class="btn btn-info btn-sm">&nbsp;éditer&nbsp;</a>
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="id" value="{{ $payement->id }}">
    <button data-what="Supprimer" data-action="{{ $type }}" class="btn btn-danger btn-sm deleteAction">x</button>
</form>