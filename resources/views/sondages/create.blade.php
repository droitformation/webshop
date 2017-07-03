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
        <p><strong>{{ $sondage->colloque->titre }} | {{ $sondage->colloque->event_date }}</strong></p>
    @endif

    <form class="form-sondage" action="{{ url('reponse') }}" method="POST">{!! csrf_field() !!}

        <input type="hidden" name="sondage_id" value="{{ $sondage->id }}" />
        <input type="hidden" name="email" value="{{ $email }}" />
        <input type="hidden" name="isTest" value="{{ $isTest }}" />

        @if(!$sondage->avis->isEmpty())
            @foreach($sondage->avis as $avis)
                <div class="form-group form-group-sondage">
				@if($avis->type != 'chapitre')
                    <label for="message" class="control-label label-question"><p class="label-question">{!! strip_tags($avis->question) !!}</p>
				@endif

                    @if($avis->type == 'text')
                        <textarea class="form-control form-control-sondage" name="reponses[{{ $avis->id }}]"></textarea>
					@elseif($avis->type == 'chapitre')
						<p class="label-question">{!! strip_tags($avis->question) !!}</p>
                    @else
                        <?php $choices = explode(',', $avis->choices); ?>
                        @foreach($choices as $choices)
                            <div class="{{ $avis->type }}">
                                <label>
                                    <input type="{{ $avis->type }}" name="reponses[{{ $avis->id }}]" value="{{ $choices }}">{{ $choices }}
                                </label>
                            </div>
                        @endforeach
                    @endif

                </div>
            @endforeach
            <hr/>
			<h3> <strong>Merci d'avoir participer.</strong></h3> 
            <button type="submit" class="btn btn-primary">Envoyer le sondage</button>

        @endif

    </form>

@stop
