@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="options text-left" style="margin-bottom: 20px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/modele') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>
        </div>
    </div>

    {{--  <div class="row">
       <div class="col-md-12">
           <div class="card card-full">
               <div class="card-body">
                <form action="{{ url('admin/modele/'.$modele->id) }}" method="POST">{!! csrf_field() !!}
                       <input type="hidden" name="_method" value="PUT">
                       <div class="form-group">
                           <label class="control-label">Titre</label>
                           <input type="text" class="form-control" name="title" value="{{ $modele->title }}">
                       </div>
                       <div class="form-group">
                           <label class="control-label">Description</label>
                           <textarea name="description" cols="20" rows="3" class="form-control">{!! $modele->description !!}</textarea>
                       </div>
                       <input name="id" type="hidden" value="{{ $modele->id }}">
                       <button type="submit" class="btn btn-info">Éditer</button>
                   </form>

                </div>
            </div>
        </div>
    </div>--}}

    <div id="appComponent">
        <create-model :avis="{{ $avis }}" :modele="{{ $modele }}"></create-model>
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