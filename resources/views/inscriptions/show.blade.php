@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Inscriptions</h2>
            <p>&nbsp;</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
           <?php
            echo '<pre>';
            print_r($inscription->user);
            print_r($inscription->colloque);
            $inscription->options->load('option');
            //$inscription->options->option->load('groupe');
            print_r($inscription->options);
            foreach($inscription->options as $options)
            {
                $options->option->load('groupe');
                print_r($options->option);
            }
            echo '</pre>';
            ?>
        </div>
    </div>

@stop