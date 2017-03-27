@extends('frontend.pubdroit.profil.index')
@section('profil')
    @parent


    <div class="row">
        <div class="col-md-12">
            <p><a href="{{ url('pubdroit/profil/colloques') }}"><span>&larr;</span> Retour à vos inscriptions</a></p>
        </div>
    </div>
    <!-- start wrapper -->
    <div class="profil-wrapper">
        @if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())

            <div class="row">
                <div class="col-md-2">
                    <a href="{{ url('colloque/'.$inscription->colloque->id) }}">
                        <img src="{{ secure_asset($inscription->colloque->frontend_illustration) }}" alt='{{ $inscription->colloque->titre }}'/>
                    </a>
                </div>
                <div class="col-md-10">
                    <h3>{{ $inscription->colloque->titre }} </h3>
                    <p class="text-muted">{{ $inscription->colloque->soustitre }}</p>
                    <p class="text-primary">{{ $inscription->colloque->location->name }}</p>
                    <p>{{ $inscription->colloque->event_date }}</p>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-md-6">
                    <h4>Date d'inscription</h4>
                    <div class="profil-info">
                        <p>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</p>
                    </div>
                    <h4>Informations</h4>
                    <div class="profil-info">
                        <dl class="dl-horizontal">
                            <dt>N° d'inscription:</dt>
                            <dd>{{ $inscription->inscription_no }}</dd>
                            <dt>Prix:</dt>
                            <dd>{{ $inscription->price_cents }}</dd>
                        </dl>
                    </div>
                    <h4>Payement</h4>
                    <div class="profil-info">
                        @if($inscription->payed_at)
                            <h5><i class="fa fa-check text-success"></i> &nbsp;Payé le {{ $inscription->payed_at->format('d/m/Y') }}</h5>
                        @else
                            <h5><i class="fa fa-exclamation-circle"></i> &nbsp;En attente</h5>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">

                    @if(!empty($inscription->documents))
                        <h4>Documents</h4>
                        <div class="profil-info">
                            @foreach($inscription->documents as $type => $annexe)
                                <?php
                                    $path = config('documents.colloque.'.$type.'');
                                    $file = 'files/colloques/'.$type.'/'.$annexe['name'];
                                    echo '<a target="_blank" href="'.secure_asset($file).'" class="btn btn-primary btn-block" style="text-align:left;"><i class="fa fa-file"></i> &nbsp;'.ucfirst($type).'</a>';
                                ?>
                            @endforeach
                        </div>
                    @endif

                    <h4>Vos choix</h4>
                    <?php setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>
                    <div class="profil-info">

                        @if(!$inscription->user_options->isEmpty())
                            <h5>Options</h5>
                            <ol>
                                @foreach($inscription->user_options as $user_options)

                                    @if(isset($user_options->option))
                                        <li>{{ $user_options->option->title }}

                                            @if($user_options->option->type == 'choix')
                                                <?php $user_options->load('option_groupe'); ?>
                                                <p class="text-info">{{ $user_options->option_groupe->text }}</p>
                                            @endif

                                        </li>
                                    @endif
                                @endforeach
                            </ol>
                        @endif

                        @if(!isset($inscription->groupe) && !$inscription->occurrences->isEmpty())
                            <h5><strong>Conférences</strong></h5>
                            @foreach($inscription->occurrences as $occurrence)
                                <dl>
                                    <dt>Titre:</dt>
                                    <dd>{{ $occurrence->title }}</dd>
                                    <dt>Lieu:</dt>
                                    <dd>{{ $occurrence->location->name }}</dd>
                                    <dt>Date:</dt>
                                    <dd>{{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}</dd>
                                </dl>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        @endif
    </div>
@endsection
