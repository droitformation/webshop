@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar" style="margin-bottom: 20px; float: left; margin-right: 20px;">
                <a href="{{ url('admin/modele') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
            </div>
            <h3>Modèle pour sondage</h3>
        </div>
    </div>

    <div id="appComponent">
        <create-model :avis="{{ $avis }}" :modele="{{ $modele }}" :current="{{ $modele->avis_vue }}"></create-model>
    </div>

 {{--   <div class="row relative">
        <div class="col-md-4">
           <div class="model-avis">
               @if(!$avis->isEmpty())
                   @foreach($avis as $question)
                       <button class="options-item">
                           <label class="d-block type_name"><small>{{ $question->type_name }}</small></label>
                           {!! $question->question !!}
                       </button>
                   @endforeach
               @endif
           </div>
        </div>
        <div class="col-md-8">

            <div class="card card-full">
                <div class="card-body">

                </div>
            </div>

        </div>
    </div>--}}

@stop