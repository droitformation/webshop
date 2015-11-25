@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-6">
            <h3>Abos</h3>
        </div>
        <div class="col-md-6 text-right">
            <div class="options" style="margin-bottom: 10px;">
                <a href="{{ url('admin/abo/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="row">
                @if(!$abos->isEmpty())
                    @foreach($abos as $abo)
                    <div class="col-md-4">
                        <div class="panel panel-midnightblue">
                            <div class="panel-body">
                                <img class="thumbnail" style="height: 80px; float:left; margin-right: 15px;padding: 5px;" src="{{ asset('files/products/'.$abo->current_product->image) }}" />
                                <h4>{{ $abo->current_product->title }} <br/> <span class="label label-grape">{{ $abo->plan_fr }}</span></h4>
                            </div>
                            <div class="panel-footer">
                                <form action="{{ url('admin/abo/'.$abo->id) }}" method="POST" class="form-horizontal">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <button data-what="Supprimer" data-action="{{ $abo->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                    <a href="{{ url('admin/abo/'.$abo->id) }}" class="btn btn-sm btn-info pull-right">Liste des abonnements</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>

@stop