@extends('sondages.layouts.master')
@section('content')

<!-- section class="row">
<!-- *************************************
Présentation du colloque avec illustration
******************************************
	   
		<div class="col-md-12">
		
				<div class="col-md-3">
					<div class="b-img-holder">
					<img src="https://www.publications-droit.ch/files/colloques/illustration/2eeeb5b32fbca0360ab48808e0ba642e.JPG" width="195"></img>
					</div>			
				</div>
				
				<div class="col-md-9">
				
							<h4><strong class="title">Formulaire d'évaluation</strong></h4>
							<h2>Les droits d’emption, de préemption et de réméré</h2><br>
							<strong>vendredi 02 juin 2017</strong>
						</div>
				</div>
</section -->
    <h3>Sondage pour {{ $sondage->colloque->titre }}</h3>

    <form class="form-sondage" action="{{ url('reponse') }}" method="POST">{!! csrf_field() !!}

        <input type="hidden" name="sondage_id" value="{{ $sondage->id }}" />
        <input type="hidden" name="email" value="{{ $email }}" />

        @if(!$sondage->avis->isEmpty())
            @foreach($sondage->avis as $avis)
                <div class="form-group form-group-sondage">
                    <label for="message" class="control-label label-question"><strong>{!! strip_tags($avis->question) !!}</strong></label>

                    @if($avis->type == 'text')
                        <textarea class="form-control form-control-sondage" name="reponses[{{ $avis->id }}]"></textarea>
					@else if($avis->type == 'chapitre')
						<h4><strong>{!! strip_tags($avis->question) !!}</strong></h4>
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
