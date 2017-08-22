@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <form action="{{ url('admin/order') }}" method="POST">{!! csrf_field() !!}
                        <input name="data" value="{{ json_encode($data) }}" type="hidden">
                        <button type="submit" class="btn btn-info">Valider la commande</button>
                    </form>

                   <?php
                    echo '<pre>';
                    print_r($data);
                    echo '</pre>';
                    ?>

                </div>
            </div>

        </div>
    </div>

@stop