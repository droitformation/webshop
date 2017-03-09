@extends('backend.layouts.master')
@section('content')

<?php $site = $sites->find($current_site); ?>

<div class="row">
    <div class="col-md-10">
        <h3>Arrêts <a href="{{ url('admin/arret/create/'.$site->id) }}" class="btn btn-success pull-right" id="addArret"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></h3>

        <div class="tab-container">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
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
                            @if(!$arrets->isEmpty())
                                @foreach($arrets as $arret)
                                    <tr>
                                        <td><a class="btn btn-sky btn-sm" href="{{ url('admin/arret/'.$arret->id) }}">éditer</a></td>
                                        <td><strong>{{ $arret->reference }}</strong></td>
                                        <td>{{ utf8_encode($arret->pub_date->formatLocalized('%d %B %Y')) }}</td>
                                        <td>{{ $arret->abstract }}</td>
                                        <td class="text-right">
                                            <form action="{{ url('admin/arret/'.$arret->id) }}" method="POST" class="form-horizontal">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <button data-what="supprimer" data-action="arrêt {{ $arret->reference }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
    <div class="col-md-2">
        @include('backend.partials.sites-menu')
    </div>
</div>

@stop