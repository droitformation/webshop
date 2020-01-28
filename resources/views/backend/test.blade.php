@extends('backend.layouts.master')
@section('content')

   <div class="row" id="appComponent">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body" id="appComponent">
                    <form id="formOrder" action="{{ url('admin/order/verification') }}" class="validate-form" data-validate="parsley" method="POST">
                        {!! csrf_field() !!}

                        <product-select :products="{{ $products }}"></product-select>

                        <button type="submit" class="btn btn-info">Vérifier la commande</button>

                    </form>

                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-body" id="appComponent">
                    <div class="form-group">
                        <label for="file" class="control-label">Image</label><br/>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal_0">Choisir un fichier</button>
                        <manager id="0" name="image" :thumbs="{{ json_encode(['products','uploads']) }}"></manager>
                    </div>
                </div>
            </div>


        </div>
    </div>

@stop