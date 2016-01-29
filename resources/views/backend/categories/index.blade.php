@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Catégories</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/categorie/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">

        @if(!$sites->isEmpty())
            <?php $categorie_sites = $categories->groupBy('site_id'); ?>

            @foreach($sites as $site)
                @if(isset($categorie_sites[$site->id]))
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <h4>{{ $site->nom }}</h4>
                            <div class="panel panel-primary">
                                <div class="panel-body">

                                    <table class="table simple" style="margin-bottom: 0px;">
                                        <thead>
                                        <tr>
                                            <th class="col-sm-2">Action</th>
                                            <th class="col-sm-3">Images</th>
                                            <th class="col-sm-3">Titre</th>
                                            <th class="col-sm-2 no-sort"></th>
                                        </tr>
                                        </thead>
                                        <tbody class="selects">
                                            @foreach($categorie_sites[$site->id] as $categorie)
                                                <tr>
                                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/categorie/'.$categorie->id) }}"><i class="fa fa-edit"></i></a></td>
                                                    <td><img height="40" src="{!! asset('files/pictos/'.$categorie->site->slug.'/'.$categorie->image) !!}" alt="{{ $categorie->title }}" /></td>
                                                    <td><strong>{{ $categorie->title }}</strong></td>
                                                    <td class="text-right">
                                                        {!! Form::open(array('id' => 'deleteCategorieForm_'.$categorie->id, 'route' => array('admin.categorie.destroy', $categorie->id), 'method' => 'delete')) !!}
                                                        {!! Form::close() !!}
                                                        <button data-id="{{ $categorie->id }}" class="btn btn-danger btn-sm deleteCategorie">x</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        @endif

    </div>
</div>


@stop