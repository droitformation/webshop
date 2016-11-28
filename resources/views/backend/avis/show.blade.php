@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/avis') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->

<div class="row">
    <div class="col-md-10">

        <!-- form start -->
        <form action="{{ url('admin/avis/'.$avis->id) }}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
            <input type="hidden" name="_method" value="PUT">
            {!! csrf_field() !!}

            <div class="panel panel-midnightblue">
                <div class="panel-body">

                    <h3>Question</h3>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Type</label>
                        <div class="col-sm-6">
                            <select name="type" class="form-control" id="sondageTypeSelect">
                                <option {{ $avis->type == 'text' ? 'selected' : '' }} value="text">Texte</option>
                                <option {{ $avis->type == 'checkbox' ? 'selected' : '' }} value="checkbox">Case à cocher</option>
                                <option {{ $avis->type == 'radio' ? 'selected' : '' }} value="radio">Options à choix</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="sondageChoices" style="display: {{ $avis->type != 'text' ? 'block' : 'none' }};">
                        <label for="message" class="col-sm-3 control-label">Choix (séparés par virgules)</label>
                        <div class="col-sm-6">
                            <textarea style="height: 100px;" name="choices" class="form-control">{{ $avis->choices }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Question</label>
                        <div class="col-sm-6">
                            <textarea name="question" required class="form-control redactorSimple">{!! $avis->question !!}</textarea>
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <input type="hidden" name="id" value="{{ $avis->id }}" />
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@stop