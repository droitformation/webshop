@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Inscription</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <?php $inscription->options->load('option'); ?>
            <?php $inscription->colloque->load('location','organisateur','compte'); ?>

            <h4>{{ $inscription->colloque->titre }} <small>{{ $inscription->colloque->soustitre }}</small></h4>
            <p class="text-primary">{{ $inscription->colloque->location->name }}</p>
            <p>{{ $inscription->colloque->event_date }}</p>
            <hr/>

            <h4>{{ $inscription->user->name }}</h4>
            <p>Inscrit le: {{ $inscription->created_at->format('d/m/Y') }}</p>

            <h4>Infos</h4>
            <p><strong>N°:</strong> {{ $inscription->inscription_no }}</p>
            <p><strong>Prix:</strong> {{ $inscription->price_cents }}</p>

            <h4>Options</h4>
            @if(!$inscription->options->isEmpty())
                @foreach($inscription->options as $options)

                    <?php $options->option->load('groupe'); ?>
                    <p>{{ $options->option->title }}</p>

                    @if($options->option->type == 'choix')
                        @foreach($options->option->groupe as $groupe)
                            <p class="text-info">{{ $groupe->text }}</p>
                        @endforeach
                    @endif

                @endforeach
            @endif

           <?php
/*         echo '<pre>';
            print_r($inscription->user);
            print_r($inscription->colloque);

            //$inscription->options->option->load('groupe');
            print_r($inscription->options);
            foreach($inscription->options as $options)
            {
                $options->option->load('groupe');
                print_r($options->option);
            }
            echo '</pre>';*/
            ?>
        </div>
    </div>

@stop