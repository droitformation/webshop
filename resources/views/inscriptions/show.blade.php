@extends('layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Inscription</h2>
            <p>&nbsp;</p>
            <?php $inscription->options->load('option'); ?>
            <?php $inscription->colloque->load('location','organisateur','compte'); ?>

            <h4>{{ $inscription->colloque->titre }} <small>{{ $inscription->colloque->soustitre }}</small></h4>
            <p class="text-primary">{{ $inscription->colloque->location->name }}</p>
            <p>{{ $inscription->colloque->event_date }}</p>
            <hr/>

        </div>
    </div>

    <div class="row inscription">
        <div class="col-md-6">

            <h4>{{ $inscription->user->name }}</h4>
            <p>Inscrit le: {{ $inscription->created_at->format('d/m/Y') }}</p>

            <h4>Infos</h4>
            <p><strong>N°:</strong> {{ $inscription->inscription_no }}</p>
            <p><strong>Prix:</strong> {{ $inscription->price_cents }}</p>

            @if($inscription->payed_at)
                <h1 class="label label-success" style="font-size: 90%;">Payé le {{ $inscription->payed_at->format('d/m/Y') }}</h1>
            @endif

            <h4>Documents</h4>
            <div class="btn-group" role="group" aria-label="...">
                @if(!empty($inscription->documents))
                    @foreach($inscription->documents as $type => $annexe)
                        <?php
                        $path = config('documents.colloque.'.$type.'');
                        $file = 'files/colloques/'.$type.'/'.$annexe['name'];
                        echo '<a target="_blank" href="'.$file.'" class="btn btn-default">'.$type.'</a>';
                        ?>
                    @endforeach
                @endif
            </div>

        </div>
        <div class="col-md-6">
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

        </div>
    </div>

@stop