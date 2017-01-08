@extends('backend.layouts.master')
@section('content')

<?php $site = $sites->find($current_site); ?>

<div class="row">
    <div class="col-md-6">
        <h3>Analyses</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/analyse/create/'.$site->id) }}" class="btn btn-success" id="addAnalyse"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class="panel panel-midnightblue">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0px;" id="generic">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Auteur(s)</th>
                            <th class="col-sm-3">Date de publication</th>
                            <th class="col-sm-4">Résumé</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                            @if(!empty($analyses))
                                @foreach($analyses as $analyse)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/analyse/'.$analyse->id) }}">éditer</a></td>
                                    <td>
                                        @if(!$analyse->authors->isEmpty())
                                            @foreach($analyse->authors as $analyse_authors)
                                                <p><strong>{{ $analyse_authors->name }}</strong></p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $analyse->pub_date->formatLocalized('%d %B %Y') }}</td>
                                    <td>{{ $analyse->abstract }}</td>
                                    <td>
                                        <form action="{{ url('admin/analyse/'.$analyse->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-what="supprimer" data-action="analyse" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
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
</div>

@stop