@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-3">
            <h3>Envoyer la campagne à une liste</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body">

                    <form action="{{ url('build/send/list') }}" method="POST">
                        {!! csrf_field() !!}
                        <input name="campagne_id" value="{{ $campagne->id }}" type="hidden">

                        <h3>Campagne:</h3>
                        <div class="well well-sm" style="padding: 5px 10px;">
                            <h5><strong><a href="{{ url('build/campagne/'.$campagne->id.'/edit') }}">{{ $campagne->sujet }}</a></strong></h5>
                            <p>{{ $campagne->auteurs }}</p>
                        </div>

                        <h3>Liste:</h3>
                        <div class="form-group">
                            <div class="col-sm-10">
                                @if(!$listes->isEmpty())
                                    <select class="form-control" name="list_id">
                                        <option value="">Choix de la liste</option>
                                        @foreach($listes as $liste)
                                            <option value="{{ $liste->id }}">{{ $liste->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-sm-2 text-right">
                                <button type="submit" class="btn btn-primary">Envoyer</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
