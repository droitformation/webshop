@extends('backend.layouts.master')
@section('content')

<?php $site = $sites->find($current_site); ?>

<div class="row">
    <div class="col-md-10">

        <h3>Analyses <a href="{{ url('admin/analyse/create/'.$site->id) }}" class="btn btn-success pull-right" id="addAnalyse"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></h3>

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
                                            <button data-what="supprimer" data-action="analyse" class="btn btn-danger btn-sm deleteAction">x</button>
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