@extends('layouts.user')
@section('content')

<div class="col-md-9 content">

    <p><a href="{{ url('profil/colloques') }}" class="btn btn-info"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp;Retour à la liste</a></p>

    <div class="panel panel-default">
        <div class="panel-heading">Inscription</div>
        <div class="panel-body">

            <h4>{{ $inscription->colloque->titre }} <small>{{ $inscription->colloque->soustitre }}</small></h4>
            <p class="text-primary">{{ $inscription->colloque->location->name }}</p>
            <p>{{ $inscription->colloque->event_date }}</p>
            <hr/>

            <div class="row inscription">
                <div class="col-md-6">
                    <h4>Date d'inscription</h4>
                    <p>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</p>

                    <h4>Infos</h4>
                    <p><strong>N°:</strong> {{ $inscription->inscription_no }}</p>
                    <p><strong>Prix:</strong> {{ $inscription->price_cents }}</p>


                    <h4>Payement</h4>

                    @if($inscription->payed_at)
                        <h1 class="label label-success" style="font-size: 90%;">Payé le {{ $inscription->payed_at->format('d/m/Y') }}</h1>
                    @else
                        <h1 class="label label-warning" style="font-size: 90%;">En attente</h1>
                    @endif

                </div>
                <div class="col-md-6">

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

                    <h4>Options</h4>

                    @if(!$inscription->user_options->isEmpty())
                        <ol>
                        @foreach($inscription->user_options as $user_options)

                            <li>{{ $user_options->option->title }}

                            @if($user_options->option->type == 'choix')
                                <?php $user_options->load('option_groupe'); ?>
                                <p class="text-info">{{ $user_options->option_groupe->text }}</p>
                            @endif

                            </li>
                        @endforeach
                        </ol>
                    @endif

                </div>
            </div>



        </div><!-- end panel body -->
    </div><!-- end panel -->

</div>

@endsection
