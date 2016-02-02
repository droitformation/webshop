<table class="table">
    <thead>
    <tr>
        <th class="col-sm-1">Action</th>
        <th class="col-sm-2">Titre</th>
        <th class="col-sm-1">Image</th>
        <th class="col-sm-1">Type</th>
        <th class="col-sm-1">Position</th>
        <th class="col-sm-1"></th>
    </tr>
    </thead>
    <tbody class="selects">
        @if(!$bloc->isEmpty())
            @foreach($bloc as $item)
                <tr>
                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/bloc/'.$item->id) }}">&Eacute;diter</a></td>
                    <td><strong>{!! $item->title !!}</strong></td>
                    <td>
                        @if(!empty($item->image))
                            <img height="50" src="{{ asset('files/uploads/'.$item->image) }}" alt="{{ $item->title or '' }}" />
                        @endif
                    </td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $positions[$item->position] }}</td>
                    <td class="text-right">
                        {!! Form::open(array('route' => array('admin.bloc.destroy', $item->id), 'method' => 'delete')) !!}
                        <button data-what="supprimer" data-action="bloc: {{ $item->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td>Aucun contenus</td></tr>
        @endif
    </tbody>
</table>
