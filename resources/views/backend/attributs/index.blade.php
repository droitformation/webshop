@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Attributs des produits</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/attribut/create') }}" id="attribut_create" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

        @if(!$attributs->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">

                    <table class="table simple-table">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-2">Titre</th>
                            <th class="col-sm-2">Interval</th>
                            <th class="col-sm-2">Visible que dans admin et utilisé comme rappel</th>
                            <th class="col-sm-4">Texte</th>
                            <th class="col-sm-2 no-sort"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                            @foreach($attributs as $attribut)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/attribut/'.$attribut->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td><strong>{{ $attribut->title }}</strong></td>
                                    <td>{{ isset($duration[$attribut->duration]) ? $duration[$attribut->duration] : '' }}</td>
                                    <td><strong>{!! $attribut->reminder ? '<label class="label label-success">oui</label>' : '' !!}</strong></td>
                                    <td>{!! $attribut->text !!}</td>
                                    <td class="text-right">
                                        @if($attribut->attributs->count() == 0)
                                            <form action="{{ url('admin/attribut/'.$attribut->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <input type="hidden" name="id" value="{{ $attribut->id }}">
                                                <button id="deleteAttribut_{{ $attribut->id }}" data-what="Supprimer" data-action="{{ $attribut->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                            </form>
                                        @else
                                            <span class="text-danger">Attribut utilisé</span>
                                        @endif
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