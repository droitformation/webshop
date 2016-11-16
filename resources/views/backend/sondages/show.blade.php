@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/sondage') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->

<div class="row">
    <div class="col-md-8">

        <!-- form start -->
        <form action="{{ url('admin/sondage/'.$sondage->id) }}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
            <input type="hidden" name="_method" value="PUT">
            {!! csrf_field() !!}

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3>Sondage</h3>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Colloque</label>
                        <div class="col-sm-6">
                            <select autocomplete="off" name="colloque_id" required class="form-control">
                                <option value="">Choisir le colloque</option>
                                @if(!$colloques->isEmpty())
                                    @foreach($colloques as $colloque)
                                        <option {{ (old('colloque_id') == $colloque->id ) || ($sondage->colloque_id == $colloque->id) ? 'selected' : '' }} value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Valide jusqu'au</label>
                        <div class="col-sm-3">
                            <input type="text" name="valid_at" value="{{ $sondage->valid_at->format('Y-m-d') }}" class="form-control datePicker required">
                        </div>
                    </div>

                </div>
            </div>
        </form>

        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4>Questions</h4>
            </div>
            <div class="panel-body">

                @if(!$sondage->questions->isEmpty())
                    <div class="sortquestion" data-id="{{ $sondage->id }}">
                        @foreach($sondage->questions as $question)
                            <div class="form-group type-choix question-item" id="question_rang_{{ $question->id }}">
                                <strong>{{ $question->question }}</strong>
                                <form action="{{ url('admin/sondage/remove/'.$question->id) }}" method="POST" class="pull-right">{!! csrf_field() !!}
                                    <input type="hidden" name="question_id" value="{{ $question->id }}" />
                                    <input type="hidden" name="sondage_id" value="{{ $question->sondage_id }}" />
                                    <button class="btn btn-danger btn-xs">Retirer</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

@stop