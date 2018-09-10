<table class="table">
    <thead>
    <tr>
        <th class="col-sm-1">Action</th>
        <th class="col-sm-6">Question</th>
        <th class="col-sm-3">Type</th>
        <th class="col-sm-2 no-sort"></th>
    </tr>
    </thead>
    <tbody class="selects">
        @foreach($avis as $question)
            <tr>
                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/avis/'.$question->id) }}"><i class="fa fa-edit"></i></a></td>
                <td><strong>{!! $question->question !!}</strong></td>
                <td>{{ $question->type_name }}</td>
                <td class="text-right">
                    <form action="{{ url('admin/avis/'.$question->id) }}" method="POST" class="form-horizontal">
                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                        <button data-what="Supprimer" data-action="La question" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>