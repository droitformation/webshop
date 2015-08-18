@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/page') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des pages</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">
    <div class="col-md-5">
        <!-- Panel -->
        <div class="panel panel-info">
            <div class="panel-body">
                <!-- Inscription simple -->
                <h2>Inscription simple</h2>
                <form role="form" class="validate-form" method="POST" action="{{ url('admin/inscription') }}" data-validate="parsley" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>

                        @if(!$colloque->prices->isEmpty())
                            @include('colloques.partials.prices', ['select' => 'price_id'])
                        @endif

                        <h4>Merci de préciser</h4>
                        @if(!$colloque->options->isEmpty())
                            @include('colloques.partials.options', ['select' => 'groupes'])
                        @endif

                        <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                        <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

                        <button class="btn btn-danger pull-right" type="submit">Envoyer</button>
                    </fieldset>
                </form>
                <!-- END Inscriptions -->
            </div>
        </div>
        <!-- END panel -->
    </div>
    <div class="col-md-7">
        <!-- Panel -->
        <div class="panel panel-midnightblue">
            <div class="panel-body">
                <!-- Inscription multiple -->
                <h2>Inscription multiple</h2>

                <form role="form" class="validate-form" method="POST" action=" registration" data-validate="parsley" >
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <p><a href="#" class="btn btn-sm btn-info" id="cloneBtn"><i class="fa fa-plus-circle"></i> &nbsp;Ajouter un participant</a></p>

                    <div id="wrapper_clone">
                        <fieldset class="field_clone" id="fieldset_clone">
                            <div class="form-group">
                                <label>Nom du participant</label>
                                <input name="participant[]" required class="form-control" value="" type="text">
                            </div>

                            @if(!$colloque->prices->isEmpty())
                                @include('colloques.partials.prices', ['select' => 'price_id[]'])
                            @endif

                            @if(!$colloque->options->isEmpty())
                                @include('colloques.partials.options', ['select' => 'groupes[]'])
                            @endif
                        </fieldset>
                    </div>

                    <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                    <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
                    <div class="clearfix"></div><br/>
                    <button class="btn btn-danger" type="submit">Envoyer</button>
                </form>

                <!-- END Inscriptions -->
            </div>
        </div>
        <!-- END panel -->
    </div>
</div>
<!-- end row -->

@stop