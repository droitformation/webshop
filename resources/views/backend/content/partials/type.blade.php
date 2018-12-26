<table class="table">
    <thead>
    <tr>
        <th class="col-sm-1">Action</th>
        <th class="col-sm-2">Titre</th>
        <th class="col-sm-1">Image</th>
        <th class="col-sm-1">Type</th>
        <th class="col-sm-1"></th>
    </tr>
    </thead>
    <tbody class="selects">
    @foreach($content as $item)
        <tr>
            <td><a class="btn btn-sky btn-sm" href="{{ url('admin/contenu/'.$item->id) }}">&Eacute;diter</a></td>
            <td><strong>{!! $item->name !!}</strong></td>
            <td>
                @if(!empty($item->image))
                    <img height="60" src="{{ secure_asset($item->image) }}" alt="{{ $item->title ?? '' }}" />
                @endif
            </td>
            <td>{{ $item->type }}</td>
            <td class="text-right">
                {!! Form::open(array('route' => array('admin.contenu.destroy', $item->id), 'method' => 'delete')) !!}
                <button data-what="supprimer" data-action="contenu: {{{ $item->title }}}" class="btn btn-danger btn-sm deleteAction">x</button>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
