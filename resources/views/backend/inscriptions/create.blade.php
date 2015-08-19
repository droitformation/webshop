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
        <div class="panel panel-magenta">
            <div class="panel-body">
                <h3>Créer une Inscription</h3>
                <form id="formInscription" class="validate-form" data-validate="parsley">
                    <div class="form-group">
                        <label><strong>Type d'inscription</strong></label>
                        <div class="radio">
                            <label><input type="radio" required name="type" value="simple" checked> Inscription simple</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" required name="type" value="multiple"> Inscription multiple</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>Colloque</strong></label>
                        <select autocomplete="off" required class="form-control" id="colloqueSelection">
                            <option value="">Choisir le colloque</option>
                            @if(!$colloques->isEmpty())
                                @foreach($colloques as $colloque)
                                    <option value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label><strong>Rechercher un utilisateur</strong></label>
                        <input id="searchUser" class="form-control" placeholder="Chercher un utilisateur..." type="text">
                    </div>

                    <div id="inputUser"></div>
                    <div id="inputolloque"></div>

                    <button type="submit" class="btn btn-info pull-right">Ok</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <!-- Panel -->
        <div class="panel panel-info">
            <div class="panel-body">
                <div id="choiceUser"></div>
                <div id="choiceColloque"></div>
                <div id="selectInscription"></div>
            </div>
        </div>
        <!-- END panel -->
    </div>

</div>



@stop