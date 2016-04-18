<form action="{{ url('admin/'.$type.'/'.$payement->id) }}" method="POST" class="pull-right">
    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
    <input type="hidden" name="id" value="{{ $payement->id }}">
    <button data-what="Supprimer" data-action="{{ $type }}" class="btn btn-danger btn-{{ $type == 'rappel' ? 'xs' : 'sm' }} deleteAction">x</button>
</form>