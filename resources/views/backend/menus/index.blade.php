@extends('backend.layouts.master')
@section('content')

<?php $site = $sites->find($current); ?>

<div class="row">
    <div class="col-md-6">
        <h3>Menus</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/menu/create/'.$site->id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <h4><img height="100%" style="width: 150px;" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" /></h4>

        <div class="panel panel-primary">
            <div class="panel-body">

                <table class="table">
                    <thead>
                    <tr>
                        <th class="col-sm-1">Action</th>
                        <th class="col-sm-2">Titre</th>
                        <th class="col-sm-1">Site</th>
                        <th class="col-sm-1">Position</th>
                        <th class="col-sm-1"></th>
                    </tr>
                    </thead>
                    <tbody class="selects">
                    @if(!$menus->isEmpty())
                        @foreach($menus as $menu)
                            <tr>
                                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/menu/'.$menu->id) }}">&Eacute;diter</a></td>
                                <td><strong>{!! $menu->title !!}</strong></td>
                                <td>{{ $menu->site->nom }}</td>
                                <td>{{ $positions[$menu->position] }}</td>
                                <td class="text-right">
                                    <form action="{{ url('admin/menu/'.$menu->id) }}" method="POST">
                                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                        <button data-what="supprimer" data-action="menu: {{ $menu->title }}" class="btn btn-danger btn-sm deleteAction">x</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td>Aucun menu</td></tr>
                    @endif
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

@stop