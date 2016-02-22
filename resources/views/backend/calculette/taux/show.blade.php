@extends('backend.layouts.master')
@section('content')


<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!! url('admin/calculette/taux') !!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ( !empty($taux) )

    <div class="col-md-6">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!! url('admin/calculette/taux/'.$taux->id) !!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Taux</label>
                        <div class="col-sm-9">
                            <input type="text" name="{!! $taux->taux !!}" value="" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Date</label>
                        <div class="col-sm-9">
                            <input type="text" name="start_at" value="{!! $taux->start_at->format('Y-m-d') !!}" class="form-control datePicker" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Canton</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="canton" required>
                                <option value="">Choix</option>
                                @foreach($calcantons as $canton_id => $canton)
                                    <option {{ $taux->canton == $canton_id ? 'selected' : '' }} value="{{ $canton_id }}">{{ $canton }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3">
                        {!! Form::hidden('id', $taux->id )!!}
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