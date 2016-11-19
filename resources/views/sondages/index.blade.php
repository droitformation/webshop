@extends('sondages.layouts.master')
@section('content')

    <h3>Sondage pour {{ $sondage->colloque->titre }}</h3>

    <form class="form-sondage" action="{{ url('admin/sondageavis') }}" method="POST">{!! csrf_field() !!}
        <input type="hidden" name="id" value="{{ $sondage->id }}" />

        @if(!$sondage->avis->isEmpty())
            @foreach($sondage->avis as $avis)
                <div class="form-group form-group-sondage">
                    <label for="message" class="control-label label-question"><strong>{!! strip_tags($avis->question) !!}</strong></label>

                </div>
            @endforeach
        @endif

    </form>

@stop