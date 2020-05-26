@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/colloque') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des colloque</a></p>
    </div>
</div>

<!-- start row -->
<div class="row">
    <div class="col-md-{{ $type == 'simple' ? 6 : 12 }}">
        <div class="panel panel-magenta">
            <div class="panel-body" id="appComponent">
                <p><a class="btn btn-warning btn-sm" href="{{ url('admin/inscription/create') }}"><i class="fa fa-arrow-circle-left"></i> &nbsp;Retour</a></p>
                <h3>Créer une inscription {{ $type }}</h3>

                <form id="formInscription" class="validate-form" data-validate="parsley" action="{{ url('admin/inscription') }}" method="post">
                    <h4><strong>Colloque:</strong> {{ $colloque->titre }}</h4>
                    {!! isset($form) ? $form : '' !!}
                </form>

            </div>
        </div>
    </div>

</div>



@stop