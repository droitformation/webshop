@extends('sondages.layouts.master')
@section('content')

    @if($isTest)
        <div class="alert alert-warning">Ceci est un sondage test</div>
    @endif

    @if($sondage->marketing)
        <h3>{{ $sondage->title }}</h3>
        {!! $sondage->description !!}
    @else
        <h3>Formulaire d'évaluation</h3>
        <h4 class="sondage-title-colloque"><strong>{{ $sondage->colloque->titre }} | {{ $sondage->colloque->event_date }}</strong></h4>
    @endif

    <form class="form-sondage" action="{{ url('reponse') }}" method="POST">{!! csrf_field() !!}

        <input type="hidden" name="sondage_id" value="{{ $sondage->id }}" />
        <input type="hidden" name="email" value="{{ $email }}" />
        <input type="hidden" name="isTest" value="{{ $isTest }}" />

        @if(!$sondage->avis->isEmpty())
            @foreach($sondage->avis as $avis)

                <div class="form-group-sondage">
                    @if($avis->type == 'chapitre')
                        <h4 class="label-chapitre"><strong>{!! strip_tags($avis->question) !!}</strong></h4>

                    @elseif($avis->type == 'text')
                        <label for="message" class="control-label">{!! $avis->question !!}</label>
                        <textarea class="form-control sondage" name="reponses[{{ $avis->id }}]"></textarea>

                    @else($avis->type == 'question')
                        <ul>
                            <li class="sondage-question">
                                <label for="message" class="control-label label-question">{!! $avis->question !!}</label>
                            </li>
                        </ul>

                        <ul class="question-{{ $avis->type  }}">
                            <?php $choices = explode(',', $avis->choices); ?>
                            @foreach($choices as $choices)
                                <li class="radio-sondage">
                                    <input class="sondage" type="{{ $avis->type }}" name="reponses[{{ $avis->id }}]" value="{{ $choices }}">{{ $choices }}
                                </li>
                            @endforeach
                        </ul>

                    @endif

                </div>
            @endforeach
            <hr class="sondage"/>
            <div class="remerciements sondage">
                <h3> <strong>Merci d'avoir participé</strong></h3>
                <button type="submit" class="btn btn-primary">Envoyer le sondage</button>
            </div>

        @endif

    </form>

@stop
