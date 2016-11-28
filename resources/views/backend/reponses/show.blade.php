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
                    <form action="{{ url('admin/reponse/'.$sondage->id) }}" method="POST" class="" data-validate="parsley"> {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Réponses au sondage</h3>
                                <p><strong>{{ $sondage->colloque->titre }}</strong></p>
                            </div>
                            <div class="col-md-6">
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
                                        <option {{ $sort == 'reponse_id' ? 'selected' : '' }} value="reponse_id">Personne</option>
                                        <option {{ $sort == 'avis_id' ? 'selected' : '' }} value="avis_id">Question</option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit">Envoyer</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr/>

                    <div class="reponses-wrapper">

                       @if(!$reponses->isEmpty())
                           @foreach($reponses as $id => $response)
                                <div class="sondage-reponse-wrapper">
                                    @if($sort == 'avis_id')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="question-title">{!! $response->first()->avis->question !!}</div>
                                                @foreach($response as $avis)
                                                    @if($avis->response->isTest)
                                                        <div class="question-reponse question-reponse-multi sondage-reponse-istest">
                                                            <span class="label label-warning">Test</span>
                                                    @else
                                                        <div class="question-reponse question-reponse-multi">
                                                    @endif
                                                        {!! $avis->reponse !!}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
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
                                    @endif
                                </div>
                            @endforeach
                       @endif
                   </div>

                </div>
            </div>
        @endif

    </div>
</div>

@stop