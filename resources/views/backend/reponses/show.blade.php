@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <p><a class="btn btn-default" href="{!!  url('admin/sondage')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des sondages</a></p>
    </div>
</div>

<div class="row">
    <div class="col-md-10 col-xs-12">

        @if($sondage)

            <div class="panel panel-primary">
                <div class="panel-body">

                    <div id="sondagePrintDiv">

                        <button type="button" id="printSondage" class="no-print btn btn btn-default btn-sm pull-right">imprimer</button>

                        <form action="{{ url('admin/reponse/'.$sondage->id) }}" method="POST" data-validate="parsley"> {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Réponses au sondage</h3>
                                    @if($sondage->marketing)
                                        <h4>{{ $sondage->title }}</h4>
                                        {!! $sondage->description !!}
                                    @else
                                        <p><strong>{{ $sondage->colloque->titre }} | {{ $sondage->colloque->event_date }}</strong></p>
                                    @endif
                                </div>
                                <div class="col-md-6 no-print">
                                    <label for="message" class="control-label">
                                        Trier par
                                        <div class="checkbox">
                                            <label>
                                                <input name="isTest" {{ $isTest ? 'checked' : '' }} value="1" type="checkbox"> Afficher les tests
                                            </label>
                                        </div>
                                    </label>
                                    <div class="input-group">
                                        <select name="sort" class="form-control">
                                            <option {{ $sort == 'avis_id' ? 'selected' : '' }} value="avis_id">Question</option>
                                            <option {{ $sort == 'reponse_id' ? 'selected' : '' }} value="reponse_id">Personne</option>
                                        </select>
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit">Envoyer</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <hr class="no-print"/>

                        <div class="reponses-wrapper">

                           @if(!$reponses->isEmpty())

                                <div class="sondage-reponse-wrapper">

                                    <!-- Sort by responses -->
                                    @if($sort == 'avis_id')

                                        <div class="row" style="page-break-after: always;">
                                            <div class="col-md-12">
                                                @foreach($reponses as $avis)
                                                    <div class="question-reponse">
                                                        @if(isset($avis['title']))
                                                            <div style="padding: 5px 0;">{!! $avis['title'] !!}</div>
                                                        @endif

                                                        @if(isset($avis['chapitre']))
                                                            <h4 style="padding-top: 10px;">{!! strip_tags($avis['chapitre']) !!}</h4>
                                                        @endif
                                                    </div>

                                                    @if(isset($avis['reponses']) && !$avis['reponses']->isEmpty())
                                                        @if($avis['type'] != 'text')
                                                        <dl class="dl-horizontal dl-sondage">
                                                            @foreach($avis['reponses'] as $q => $note)
                                                                <dt>{{ $q }}: </dt>
                                                                <dd>{{ $note }}</dd>
                                                            @endforeach
                                                        </dl>
                                                        @else
                                                            <ul>
                                                                @foreach($avis['reponses'] as $note)
                                                                    <li>{{ $note }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    @endif

                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($sort == 'reponse_id')
                                        @foreach($reponses as $id => $response)
                                            <!-- Sort by person -->
                                            <div class="row {{ $response->first()->response->isTest ? 'sondage-reponse-istest' : '' }}">
                                                <div class="col-md-4">
                                                    <h4>
                                                        {!! $response->first()->response->isTest ? '<span class="label label-warning">Test</span>' : '' !!}
                                                        {{ $response->first()->response->email }}
                                                    </h4>
                                                </div>
                                                <div class="col-md-8">
                                                    @foreach($response as $avis)
                                                        <div class="question-title">{!! $avis->avis->question !!}</div>
                                                        <div class="question-reponse">{!! $avis->reponse !!}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>

                           @endif
                       </div>

                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

@stop