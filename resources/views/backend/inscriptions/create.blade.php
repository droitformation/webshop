@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/colloque') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des colloque</a></p>
    </div>
</div>

<!-- start row -->
<div class="row">
    <div class="col-md-7">
        <div class="panel panel-magenta">
            <div class="panel-body">
                <h3>Créer une Inscription</h3>

                <form id="formInscription" class="validate-form" data-validate="parsley" action="{{ url('admin/inscription/make') }}" method="post">
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <label><strong>Type d'inscription</strong></label>
                        <div class="radio">
                            <label><input type="radio" required name="type" value="simple" {{ old('type') == 'simple' || !old('type') ? 'checked': '' }}> Inscription simple</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" required name="type" value="multiple" {{ old('type') == 'multiple' ? 'checked': '' }}> Inscription multiple</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Colloque</strong></label>
                        <select autocomplete="off" name="colloque_id" required class="form-control select-chosen" id="colloque_id">
                            <option value="">Choisir le colloque</option>
                            @if(!$colloques->isEmpty())
                                @foreach($colloques as $col)
                                    <option {{ (old('colloque_id') == $col->id ) || ($colloque_id == $col->id) ? 'selected' : '' }} value="{{ $col->id }}">{{ $col->titre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <h5>Détenteur de l'inscription</h5>
                    <!-- Search user autocomplete -->
                    @include('backend.partials.search-user')

                    <div id="inputColloque"></div>
                    <div id="choiceColloque"></div>

                    <button type="submit" class="btn btn-info pull-right">Suivant</button>
                </form>

            </div>
        </div>
    </div>

</div>



@stop