@extends('sondages.layouts.master')
@section('content')

    <h3>Sondage pour {{ $sondage->colloque->titre }}</h3>

    <form class="form-sondage" action="{{ url('reponse') }}" method="POST">{!! csrf_field() !!}

        <input type="hidden" name="sondage_id" value="{{ $sondage->id }}" />
        <input type="hidden" name="email" value="{{ $email }}" />
        <input type="hidden" name="isTest" value="{{ $isTest }}" />

        @if(!$sondage->avis->isEmpty())
            @foreach($sondage->avis as $avis)
                <div class="form-group form-group-sondage">
                    <label for="message" class="control-label label-question"><strong>{!! strip_tags($avis->question) !!}</strong></label>

                    @if($avis->type == 'text')
                        <textarea class="form-control form-control-sondage" name="reponses[{{ $avis->id }}]"></textarea>
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
            <button type="submit" class="btn btn-primary">Envoyer le sondage</button>

        @endif

    </form>

@stop