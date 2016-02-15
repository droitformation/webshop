@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/analyse/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4><i class="fa fa-edit"></i> &nbsp;Analyses</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0px;" id="generic">
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Auteur</th>
                            <th class="col-sm-3">Date de publication</th>
                            <th class="col-sm-4">Résumé</th>
                            <th class="col-sm-1"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">
                        <?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>
                        @if(!empty($analyses))
                            @foreach($analyses as $analyse)
                            <tr>
                                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/analyse/'.$analyse->id) }}">éditer</a></td>
                                <td>
                                    @if(isset($analyse->analyse_authors))
                                        @foreach($analyse->analyse_authors as $analyse_authors)
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