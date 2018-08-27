@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('admin/faq/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une catégorie</a>
    </div>
</div>

<?php $grouped_cats = $cats->groupBy('site_id'); ?>

@if(!$sites->isEmpty())
    @foreach($sites as $site)
        <div class="row">
            <div class="col-md-12">

                <p><img height="40" src="{{ secure_asset('logos/'.$site->logo) }}" alt="logo"></p>
                <div class="panel panel-primary">
                    <div class="panel-body">

                        @if(isset($grouped_cats[$site->id]))
                            <?php $chunks = $grouped_cats[$site->id]->chunk(3); ?>
                            @foreach($chunks as $chunk)
                                <div class="row" style="margin-bottom: 15px;">
                                    @foreach($chunk as $categorie)
                                        <div class="col-md-4">
                                            <h5>
                                                <strong>{{ $categorie->title }}</strong>&nbsp;
                                                <a href="{{ url('admin/question/create/'.$categorie->id) }}" class="btn btn-success btn-xs pull-right">Ajouter une question</a>
                                            </h5>

                                            @if(!$categorie->questions->isEmpty())
                                                <div class="list-group" style="margin-bottom: 5px;">
                                                    @foreach($categorie->questions as $question)
                                                        <div class="list-group-item">
                                                            <div class="row">
                                                                <div class="col-md-9">{{ $question->title }}</div>
                                                                <div class="col-md-3 text-right">
                                                                    <form action="{{ url('admin/question/'.$question->id) }}" method="POST">
                                                                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                                        <a href="{{ url('admin/question/'.$question->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                                                        <button data-what="Supprimer" data-action="{{ $question->title }}" class="btn btn-xs btn-danger btn-delete deleteAction">X</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <form action="{{ url('admin/faq/'.$categorie->id) }}" method="POST" class="form-horizontal text-right">
                                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                <a href="{{ url('admin/faq/'.$categorie->id) }}" class="btn btn-info btn-xs">Editer la catégorie</a>
                                                <button data-what="Supprimer" data-action="{{ $categorie->title }}" class="btn btn-xs btn-danger btn-delete deleteAction">Supprimer la catégorie</button>
                                            </form>

                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>

            </div>
        </div>
    @endforeach
@endif



@stop