@extends('backend.layouts.master')
@section('content')

<?php $site = $sites->find($current); ?>

<div class="row">
    <div class="col-md-6">
        <h3>Catégories</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/categorie/create/'.$site->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h4><img height="100%" style="width: 150px;" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" /></h4>
                @if(!$categories->isEmpty())

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
                                    @foreach($categories as $categorie)
                                        <tr>
                                            <td><a class="btn btn-sky btn-sm" href="{{ url('admin/categorie/'.$categorie->id) }}"><i class="fa fa-edit"></i></a></td>
                                            <td><img height="40" src="{!! asset('files/pictos/'.$categorie->image) !!}" alt="{{ $categorie->title }}" /></td>
                                            <td><strong>{{ $categorie->title }}</strong></td>
                                            <td class="text-right">
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
        </div>
    </div>
</div>


@stop