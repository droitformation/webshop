@extends('backend.layouts.master')
@section('content')
    <?php $helper = new \App\Droit\Helper\Helper(); ?>

    <p>
        <?php $back = session()->has('colloque_archive') ? url('admin/colloque/archive/'.session()->get('colloque_archive')) : url('admin/colloque'); ?>
        <a href="{{ $back }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux colloques</a>
        <a href="{{ url('admin/inscription/colloque/'.$colloque->id) }}" class="btn btn-success"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux inscriptions</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-5">
                    <?php $illustration = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                    <img class="thumbnail" style="height: 40px; float:left; margin-right: 15px;padding: 2px;" src="{{ secure_asset('files/colloques/illustration/'.$illustration) }}" />
                    <h3 style="margin-bottom:0px;line-height:20px;font-size: 18px;"><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a></h3>
                    <p style="margin-bottom: 0;">{{ $colloque->event_date }}</p>
                </div>
                <div class="col-md-3" style="border-right: 1px solid #cecece;">
                    <form action="{{ url('admin/inscription/rappel/make') }}" method="POST" class="form-inline">{!! csrf_field() !!}
                        <input type="hidden" name="colloque_id" value="{{ $colloque->id }}">
                        <div class="checkbox text-right" style="border-left: 1px solid #cecece; padding-left: 10px;">
                            <label><input name="more" value="1" type="checkbox"> &nbsp;<strong>Ajouter 1 rappel</strong> <br/>aux rappels existants</label>
                        </div>
                        <button class="btn btn-brown pull-right" type="submit"><i class="fa fa-bell"></i> &nbsp;Générer tous les rappels</button>
                    </form>
                </div>
                <div class="col-md-2">
                    <a href="{{ url('admin/register/rappel/toprint/'.$colloque->id) }}" class="btn btn-primary pull-right">
                        <i class="fa fa-download"></i> &nbsp; Rappels + BV
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="{{ url('admin/register/rappel/confirmation/'.$colloque->id) }}" class="btn btn-inverse pull-right" >
                        <i class="fa fa-paper-plane"></i> &nbsp; Confirmer la liste et envoyer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body"  id="appComponent">
                    <h3><i class="fa fa-gavel"></i> &nbsp;Rappel inscriptions </h3>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="col-sm-2">No</th>
                                <th class="col-sm-2">Prix</th>
                                <th class="col-sm-1">Facture</th>
                                <th class="col-sm-2">Date</th>
                                <th class="col-md-5">Rappels</th>
                            </tr>
                            </thead>
                            <tbody class="selects" id="appComponent">

                                @if(!$inscriptions->isEmpty())
                                    @foreach($inscriptions as $inscription)
                                        @if($inscription->group_id)
                                            @include('backend.inscriptions.rappels.partials.multiple', ['id' => $inscription->id, 'group' => $inscription->groupe])
                                        @else
                                            @include('backend.inscriptions.rappels.partials.simple', ['inscription' => $inscription])
                                        @endif
                                    @endforeach
                                @endif

                            </tbody>
                        </table><!-- End inscriptions -->
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if(!$archives->isEmpty())
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-body">
                        <h4>Archives pour consultation</h4>
                        <p>Repris depuis l'ancien système (Typo3)</p>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="col-sm-3">No</th>
                                    <th class="col-sm-2">Prix</th>
                                    <th class="col-sm-2">Date</th>
                                    <th class="col-md-2">Rappel en pdf</th>
                                    <th class="col-md-3">Statut facture</th>
                                </tr>
                                </thead>
                                <tbody class="selects">

                                    @foreach($archives as $archive)
                                        <tr>
                                            <td>
                                                {!! $archive['user']->trashed() ? '<span class="label label-danger"><i class="fa fa-exclamation"></i> Utilisateur supprimé</span>' : '' !!}
                                                <strong>{{ $archive['user']->name }}</strong><br/>
                                                {{ $archive['inscription']->inscription_no }}
                                            </td>
                                            <td>{{ $archive['inscription']->price_cents }} CHF</td>
                                            <td>{{ $archive['inscription']->created_at->formatLocalized('%d %b %Y') }}</td>
                                            <td><a href="{{ url($archive['file']) }}">Rappel pdf</a></td>
                                            <td>{!! $archive['inscription']->payed_at ? '<span class="label label-success">payé</span>' : 'en attente' !!}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table><!-- End inscriptions -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

@stop