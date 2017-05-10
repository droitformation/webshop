@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-3">
            <h3>Envoyer le sondage à une liste</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body">

                    <form action="{{ url('admin/sondage/send') }}" method="POST">{!! csrf_field() !!}
                        <input name="sondage_id" value="{{ $sondage->id }}" type="hidden">

                        <h3>Sondage:</h3>
                        <div class="well well-sm" style="padding: 5px 10px;">
                            <h5><strong>{{ !$sondage->marketing ? 'Colloque' : 'Marketing' }}</strong></h5>
                            {!! !$sondage->marketing ? '<p><strong>'.$sondage->colloque->titre.'</strong></p>' : $sondage->description !!}
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
