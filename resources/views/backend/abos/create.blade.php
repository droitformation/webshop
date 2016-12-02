@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/abo') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Ajouter abo</h4>
                </div>
                <form action="{{ url('admin/abo') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="price">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-3 control-label">Centre/institut</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">facultatif</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Compte pour BV</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="compte" value="{{ \Registry::get('abo.compte') }}">
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">Par défaut le compte est celui indiqué dans la configuration des abos</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Adresse pour BV</label>
                            <div class="col-sm-3 col-xs-6">
                                <textarea class="form-control redactorSimple" name="adresse">{{ \Registry::get('abo.infos.adresse') }}</textarea>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">Par défaut l'adresse est celle indiquée dans la configuration des abos</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Récurrence</label>
                            <div class="col-sm-3 col-xs-6">
                                <select class="form-control" name="plan">
                                    <option value=""></option>
                                    @foreach($plans as $name => $plan)
                                        <option {{ $name == 'year' ? 'selected' : '' }} value="{{ $name }}">{{ $plan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Logo</label>
                            <div class="col-sm-7">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        {!! Form::file('file')!!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Produits</label>
                            <div class="col-sm-5 col-xs-8">
                                <select multiple class="form-control" id="multi-select" name="products_id[]">
                                    @if(!empty($products))
                                        @foreach($products as $index => $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <p class="help-block">Seul les livres ayant les éléments de références pour la facture sont affichés</p>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button id="createAbo" type="submit" class="btn btn-info">Enregistrer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop