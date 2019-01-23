@extends('backend.layouts.master')
@section('content')

   <div class="row">
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

        </div>
    </div>

@stop