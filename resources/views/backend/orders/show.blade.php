@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-7">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/order') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Editer le produit</h4>
                </div>
                <form action="{{ url('admin/order/'.$order->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" value="{{ $order->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Poids maximum</label>
                            <div class="col-sm-5 col-xs-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $order->weight }}" name="weight">
                                    <span class="input-group-addon">grammes</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" value="{{ $order->price_cents }}" name="price">
                            </div>
                        </div>

                        <input type="hidden" value="{{ $order->id }}" name="id">

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop