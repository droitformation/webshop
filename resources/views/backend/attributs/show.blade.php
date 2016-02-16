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

    <div class="col-md-6">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!! url('admin/attribut/'.$attribut->id) !!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter {!! $attribut->title !!}</h4>
                </div>
                <div class="panel-body event-info">
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" value="{{ $attribut->title }}" class="form-control" required>
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