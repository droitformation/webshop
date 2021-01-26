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
                      {{--  @if(!$stats->isEmpty())
                            <ul>
                                @foreach($stats as $date => $tracking)
                                    <li>{{ $tracking->count() }} emails envoyés le <strong>{{ $tracking->first()->time->formatLocalized('%d %B %Y à %I:%M:%S') }}</strong></li>
                                @endforeach
                            </ul>
                        @endif--}}
                        @if(!empty($mailgun_stats))
                            <div class="row margeUpDown"><!-- start row -->
                                <div class="col-md-2">
                                    <a href="#" class="info-tiles tiles-midnightblue">
                                        <div class="tiles-heading"><div class="pull-left">Envoyés</div></div>
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-location-arrow"></i></div>
                                            <div class="pull-right"><span>{{ $statistiques['total'] ?? 0 }}</span></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="#" class="info-tiles tiles-success">
                                        <div class="tiles-heading"><div class="pull-left">Ouverts</div></div>
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-check"></i></div>
                                            <div class="pull-right"><span>{{ $statistiques['opened'] ?? 0 }}%</span></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="#" class="info-tiles tiles-info">
                                        <div class="tiles-heading"><div class="pull-left">Cliqués</div></div>
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-link"></i></div>
                                            <div class="pull-right"><span>{{ $statistiques['clicked'] ?? 0 }}%</span></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <a href="#" class="info-tiles tiles-orange">
                                        <div class="tiles-heading"><div class="pull-left">Refusés</div></div>
                                        <div class="tiles-body">
                                            <div class="pull-left"><i class="fa fa-minus-circle"></i></div>
                                            <div class="pull-right"><span>{{ $statistiques['bounced'] ?? 0 }}%</span></div>
                                        </div>
                                    </a>
                                </div>
                            </div><!-- end row -->


                           {{-- <ul>
                                @foreach($mailgun_stats as $date => $mailgun_stat)
                                    <li>{{ $mailgun_stat['stats']['delivered'] }} emails envoyés
                                        @foreach($mailgun_stat['time'] as $time)
                                           <p style="margin-bottom: 0;">
                                               Envoyé à la liste: {{ $time['liste'] }} le <strong>{{ $time['day'] }}</strong>
                                           </p>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>--}}
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
