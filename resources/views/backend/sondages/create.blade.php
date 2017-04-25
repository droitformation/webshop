@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/theme')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-6">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/sondage') }}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

                <div class="panel panel-midnightblue">
                    <div class="panel-body">

                        <h3>Sondage</h3>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Colloque</label>
                            <div class="col-sm-6">
                                <select autocomplete="off" name="colloque_id" class="form-control">
                                    <option value="">Choisir le colloque</option>
                                    @if(!$colloques->isEmpty())
                                        @foreach($colloques as $colloque)
                                            <option {{ (old('colloque_id') == $colloque->id ) ? 'selected' : '' }} value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Valide jusqu'au</label>
                            <div class="col-sm-3">
                                <input type="text" name="valid_at" value="" class="form-control datePicker required">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer mini-footer ">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>
<!-- end row -->

@stop