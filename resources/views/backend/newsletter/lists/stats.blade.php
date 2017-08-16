@extends('backend.layouts.master')
@section('content')


    <div class="row">
        <div class="col-md-3">
            <h3>Statistiques d'envois</h3>
            <div class="options" style="margin-bottom: 10px;">
                <a href="{{ url('build/newsletter') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i>  &nbsp;&nbsp;Retour aux newsletter</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body">

                        <h3>Campagne:</h3>
                        <div class="well well-sm" style="padding: 5px 10px;">
                            <h5><strong><a href="{{ url('build/campagne/'.$campagne->id.'/edit') }}">{{ $campagne->sujet }}</a></strong></h5>
                            <p>{{ $campagne->auteurs }}</p>
                        </div>

                        <h3>Tracking:</h3>
                        @if(!$stats->isEmpty())
                            <ul>
                                @foreach($stats as $date => $tracking)
                                    <li>{{ $tracking->count() }} emails envoyés le <strong>{{ $tracking->first()->time->formatLocalized('%d %B %Y à %I:%M:%S') }}</strong></li>
                                @endforeach
                            </ul>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
