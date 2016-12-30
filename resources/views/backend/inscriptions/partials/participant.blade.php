<div class="media">
    <div class="media-left">
        <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
            <input type="hidden" name="_method" value="DELETE">
            <a class="btn btn-sky btn-xs" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}"><i class="fa fa-edit"></i></a>
            <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-xs deleteAction">X</button>
        </form>
        @include('backend.users.modals.edit', ['inscription' => $inscription])
    </div>
    <div class="media-body">
        <p><strong>{!! $inscription->participant->name !!}</strong></p>
        <p>
            {{ $inscription->inscription_no }} &nbsp;
            @include('backend.partials.toggle', ['inscription' => $inscription, 'id' => $inscription->id])
        </p>
    </div>
</div>