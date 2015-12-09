@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/abo') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;&Eacute;diter abo</h4>
                </div>
                <form action="{{ url('admin/abo/'.$abo->id) }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="{{ $abo->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Centre/institut</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="name" value="{{ $abo->name }}">
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">facultatif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Récurrence</label>
                            <div class="col-sm-3 col-xs-6">
                                <select class="form-control" name="plan">
                                    <option value=""></option>
                                    @foreach($plans as $name => $plan)
                                        <option {{ $name ==  $abo->plan ? 'selected' : '' }} value="{{ $name }}">{{ $plan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(!empty($abo->logo ))
                            <div class="form-group">
                                <label for="file" class="col-sm-3 control-label">Fichier</label>
                                <div class="col-sm-3">
                                    <div class="list-group">
                                        <div class="list-group-item text-center">
                                            <a href="#"><img height="120" src="{!! asset('files/main/'.$abo->logo) !!}" alt="logo" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Changer le logo</label>
                            <div class="col-sm-7">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        {!! Form::file('file') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Produits</label>
                            <div class="col-sm-5 col-xs-8">
                                <select multiple class="form-control" id="multi-select" name="products_id[]">
                                    <?php $product_abos = !$abo->products->isEmpty() ? $abo->products->lists('id')->all() : []; ?>
                                    @if(!$products->isEmpty())
                                        @foreach($products as $product)
                                            <option {{ in_array($product->id,$product_abos) ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        {!! Form::hidden('id', $abo->id ) !!}
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop