@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12 text-right">
        <a href="{{ url('admin/faq/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une catégorie</a>
    </div>
</div>

<?php $site = $sites->find($current_site); ?>

<h3>FAQ</h3>

@if(!$cats->isEmpty())
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-body">
                    @foreach($cats as $categorie)

                        <div class="row">
                            <div class="col-md-8">
                                <h4 style="margin-top: 0;"><strong>{{ $categorie->title }}</strong></h4>
                            </div>
                            <div class="col-md-4">
                                <form action="{{ url('admin/faq/'.$categorie->id) }}" method="POST" class="form-horizontal text-right" style="margin-bottom: 20px;">
                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                    <a href="{{ url('admin/question/create/'.$categorie->id) }}" class="btn btn-success btn-xs">Ajouter une question</a>
                                    <a href="{{ url('admin/faq/'.$categorie->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                    <button data-what="Supprimer" data-action="{{ $categorie->title }}" class="btn btn-xs btn-danger btn-delete deleteAction">x</button>
                                </form>
                            </div>
                        </div>

                        @if(!$categorie->questions->isEmpty())
                            <div class="list-group" style="margin:0 5px 5px 5px;">
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
                            </div><br/><br/>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif


@stop