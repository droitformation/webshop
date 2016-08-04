@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/seminaire') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/seminaire') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Ajouter seminaire</h4>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Choix du livre</label>
                            <div class="col-sm-7 col-xs-12">
                                @if(!$products->isEmpty())
                                    <select name="product_id" class="form-control">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Choix des colloque</label>
                            <div class="col-sm-7 col-xs-12">
                                @if(!$colloques->isEmpty())
                                    <select name="colloque_id[]" multiple class="form-control">
                                        <option value="">Choix</option>
                                        @foreach($colloques as $colloque)
                                            <option value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-6">
                                <input type="text" required class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Année</label>
                            <div class="col-sm-5 col-xs-6">
                                <input type="text" required class="form-control" name="year">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Image</label>
                            <div class="col-sm-7">
                                <div class="list-group">
                                    <div class="list-group-item">{!!  Form::file('file')!!}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Créer un seminaire</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop