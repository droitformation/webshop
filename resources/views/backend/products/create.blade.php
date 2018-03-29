@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
           <p> <a href="{{ url('admin/products/back')}}" class="btn btn-sm btn-default">Retour à la liste</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Ajouter produit</h4>
                </div>
                <form action="{{ url('admin/product') }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                    {!! csrf_field() !!}

                    <div class="panel-body">

                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Image</label>
                            <div class="col-sm-7">
                                <div id="lblSize" class="alert alert-danger" style="display: none;"></div>
                                <input type="file" name="file" id="flUpload">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">&Eacute;dition en pdf</label>
                            <div class="col-sm-8">
                                {!! Form::file('download_link') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-7 col-xs-6">
                                <input type="text" class="form-control" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Teaser</label>
                            <div class="col-sm-7">
                                <textarea style="height: 100px;" class="form-control" name="teaser"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-7">
                                <textarea name="description" class="form-control redactor"></textarea>
                            </div>
                        </div>

                        <div class="form-group well well-small">
                            <label class="col-sm-3 control-label">Lien de commande externe</label>
                            <div class="col-sm-7 col-xs-6">
                                <input type="text" class="form-control" name="url" placeholder="http://">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Poids</label>
                            <div class="col-sm-3 col-xs-6">
                                <div class="input-group">
                                    <input type="text" required class="form-control" name="weight">
                                    <span class="input-group-addon">grammes</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Prix</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" name="price">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Stock</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="0" name="sku">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Date d'édition</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control datePicker" value="" name="edition_at">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Pages</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="0" name="pages">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Type de reliure</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="" name="reliure">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Format</label>
                            <div class="col-sm-3 col-xs-6">
                                <input type="text" class="form-control" value="" name="format">
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" id="addProduct" class="btn btn-info">Créer le produit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop