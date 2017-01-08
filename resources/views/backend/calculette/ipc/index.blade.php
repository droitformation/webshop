@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Calculette IPC</h3>

        <p class="text-right">
            <a id="addCalculetteIpc" href="{{ url('admin/calculette/ipc/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter IPC</a>
        </p>

    </div>
</div>

<div class="row">
    <div class="col-md-6 col-xs-12">

        @if(!$ipcs->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">

                    <table class="table">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Indice</th>
                            <th class="col-sm-3">Date</th>
                            <th class="col-sm-2 no-sort"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                            @foreach($ipcs as $ipc)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/calculette/ipc/'.$ipc->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td><strong>{{ $ipc->indice }}</strong></td>
                                    <td><strong>{{ $ipc->start_at->format('Y-m-d') }}</strong></td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/calculette/ipc/'.$ipc->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="Supprimer" data-action="{{ $ipc->indice }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        @endif

    </div>

</div>


@stop