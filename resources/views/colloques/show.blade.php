@extends('layouts.colloque')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Colloques</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <?php
            echo '<pre>';
            print_r($colloque);
            echo '</pre>';
            ?>

        </div>
    </div>

@stop