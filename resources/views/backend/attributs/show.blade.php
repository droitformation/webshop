@extends('backend.layouts.master')
@section('content')


<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!! url('admin/attribut') !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if(!empty($attribut))

    <div class="col-md-8">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!! url('admin/attribut/'.$attribut->id) !!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-body">
                    <h4>&Eacute;diter {!! $attribut->title !!}</h4>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" value="{{ $attribut->title }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><strong>Utilisé comme rappel</strong></label>
                        <div class="col-sm-5">
                            <label class="radio-inline"><input type="radio" {{  $attribut->reminder ? 'checked' : '' }} name="reminder" value="1"> Oui</label>
                            <label class="radio-inline"><input type="radio" {{ !$attribut->reminder ? 'checked' : '' }} name="reminder" value="0"> Non</label>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted">Visible que dans l'admin</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Interval</label>
                        <div class="col-sm-7">
                            <select class="form-control" name="duration">
                                <option value="">Choix</option>
                                <option {{ $attribut->duration == 'week' ? 'selected' : '' }} value="week">1 semaine</option>
                                <option {{ $attribut->duration == 'month' ? 'selected' : '' }} value="month">1 mois</option>
                                <option {{ $attribut->duration == 'trimester' ? 'selected' : '' }} value="trimester">3 mois</option>
                                <option {{ $attribut->duration == 'semester' ? 'selected' : '' }} value="semester">6 mois</option>
                                <option {{ $attribut->duration == 'year' ? 'selected' : '' }} value="year">1 an</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="text" class="col-sm-3 control-label">Text pour le rappel</label>
                        <div class="col-sm-9">
                            <textarea name="text" cols="50" rows="4" class="redactorSimple form-control">{!! $attribut->text !!}</textarea>
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3">
                        {!! Form::hidden('id', $attribut->id )!!}
                    </div>
                    <div class="col-sm-9">
                        <button class="btn btn-primary" type="submit">Envoyer </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @endif

</div>
<!-- end row -->

@stop