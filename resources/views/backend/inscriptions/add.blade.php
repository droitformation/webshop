@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/inscription') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des inscriptions</a></p>
    </div>
</div>

<!-- start row -->
<div class="row">
    <div class="col-md-5">
        <div class="panel panel-magenta">
            <div class="panel-body">
                <h3>Ajouter une Inscription au groupe</h3>
                <form data-validate-parsley action="{{ url('admin/group') }}" method="POST" class="form" >
                    {!! csrf_field() !!}

                    <input type="hidden" name="group_id" value="{{ $group_id }}" />
                    <h4>Détenteur: {!! $groupe->name !!}</h4>

                    <div class="form-group">
                        <label><strong>Colloque</strong></label>
                        <p>{{ $colloque->titre }}</p>
                        <input type="hidden" name="colloque_id" value="{{ $colloque->id }}" />
                    </div>

                    <div class="form-group">
                        <label>Nom du participant</label>
                        <input name="participant" required class="form-control" value="" type="text">
                    </div>

                    @if(!$colloque->prices->isEmpty())
                        @include('backend.inscriptions.partials.prices', ['select' => 'price_id'])
                    @endif

                    <!-- Occurence if any -->
                    @if(!$colloque->occurrences->isEmpty())
                        <h4>Conférences</h4>
                        @include('backend.inscriptions..partials.occurrences', ['select' => 'occurrences[]'])
                    @endif

                    @if(!$colloque->options->isEmpty())
                        <h4>Merci de préciser</h4>
                        @include('backend.inscriptions.partials.options', ['select' => 'groupes', 'add' => true])
                    @endif

                    <button type="submit" class="btn btn-info pull-right">Ok</button>
                </form>
            </div>
        </div>
    </div>

</div>



@stop