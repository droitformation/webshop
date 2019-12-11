@extends('backend.layouts.master')
@section('content')

<?php $site = $sites->find($current_site); ?>

<div class="row">
    <div class="col-md-10 col-xs-12">

        <h3>Catégories  <a href="{{ url('admin/categorie/create/'.$site->id) }}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></h3>

        @if(!$categories->isEmpty())

            <div class="panel panel-primary">
                <div class="panel-body">

                    <p class="indication">
                        <span class="bg-warning"></span>
                        Catégories cachés sur le site
                    </p>

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
                            @foreach($categories as $categorie)
                                <tr>
                                    <td class="{{ $categorie->hideOnSite ? 'bg-warning' : '' }}"><a class="btn btn-sky btn-sm" href="{{ url('admin/categorie/'.$categorie->id) }}"><i class="fa fa-edit"></i></a></td>
                                    <td class="{{ $categorie->hideOnSite ? 'bg-warning' : '' }}"><img height="40" src="{!! secure_asset('files/pictos/'.$categorie->image) !!}" alt="{{ $categorie->title }}" /></td>
                                    <td class="{{ $categorie->hideOnSite ? 'bg-warning' : '' }}"><strong>{{ $categorie->title }}</strong></td>
                                    <td class="text-right {{ $categorie->hideOnSite ? 'bg-warning' : '' }}">
                                        <form id="deleteCategorieForm_" action="{{ url('admin/categorie/'.$categorie->id) }}" method="POST" class="form-horizontal">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                        </form>
                                        <button data-id="{{ $categorie->id }}" class="btn btn-danger btn-sm deleteCategorie">x</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        @else
            <p>Aucune catégories</p>
        @endif
    </div>
    <div class="col-md-2">
        @include('backend.partials.sites-menu')
    </div>
</div>


@stop