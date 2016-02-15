@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Arrêts</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/arret/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tab-container">
            <ul class="nav nav-tabs">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <li class="{{ $site->id == 1 ? 'active' : '' }}">
                            <a data-toggle="tab" href="#site{{ $site->id }}"><img height="25px" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" /></a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="tab-content">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <div id="site{{ $site->id }}" class="tab-pane {{ $site->id == 1 ? 'active' : '' }}">

                            <table class="table simple" style="margin-bottom: 0px;">
                                <thead>
                                <tr>
                                    <th class="col-sm-1">Action</th>
                                    <th class="col-sm-2">Référence</th>
                                    <th class="col-sm-2">Date de publication</th>
                                    <th class="col-sm-6">Résumé</th>
                                    <th class="col-sm-1 no-sort"></th>
                                </tr>
                                </thead>
                                <tbody class="selects">
                                    <?php $arret_sites = $arrets->groupBy('site_id'); ?>
                                    @if(isset($arret_sites[$site->id]))
                                        @foreach($arret_sites[$site->id] as $arret)
                                            <tr>
                                                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/arret/'.$arret->id) }}">éditer</a></td>
                                                <td><strong>{{ $arret->reference }}</strong></td>
                                                <td>{{ $arret->pub_date->formatLocalized('%d %B %Y') }}</td>
                                                <td>{{ $arret->abstract }}</td>
                                                <td>
                                                    <form action="{{ url('admin/arret/'.$arret->id) }}" method="POST" class="form-horizontal">
                                                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                        <button data-what="supprimer" data-action="arrêt {{ $arret->reference }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@stop