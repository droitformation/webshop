@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <p><a href="{{ url('admin/seminaire') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">

            <div class="panel panel-midnightblue">
                <form action="{{ url('admin/seminaire/'.$seminaire->id) }}" enctype="multipart/form-data" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

                    <div class="panel-body">
                        <h4><i class="fa fa-edit"></i> &nbsp;Editer seminaire</h4>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Choix du livre</label>
                            <div class="col-sm-7 col-xs-12">
                                @if(!$products->isEmpty())
                                    <select name="product_id" class="form-control">
                                        @foreach($products as $product)
                                            <option {{ $product->id ==  $seminaire->product_id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Choix des colloque</label>
                            <div class="col-sm-7 col-xs-12">
                                @if(!$colloques->isEmpty())
                                    <select name="colloque_id[]" multiple class="form-control">
                                        <option value="">Choix</option>
                                        @foreach($colloques as $colloque)
                                            <option {{ $seminaire->colloques->contains($colloque->id) ? 'selected' : '' }} value="{{ $colloque->id }}">{{ $colloque->titre }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-5 col-xs-8">
                                <input type="text" class="form-control" value="{{ $seminaire->title }}" name="title">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Année</label>
                            <div class="col-sm-3 col-xs-5">
                                <input type="text" required class="form-control" value="{{ $seminaire->year }}" name="year">
                            </div>
                        </div>

                        @if(!empty($seminaire->image ))
                            <div class="form-group">
                                <label for="file" class="col-sm-3 control-label">Fichier</label>
                                <div class="col-sm-3">
                                    <div class="list-group">
                                        <div class="list-group-item text-center">
                                            <a href="#"><img height="120" src="{!! secure_asset('files/seminaires/'.$seminaire->image) !!}" alt="{{ $seminaire->title }}" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="file" class="col-sm-3 control-label">Changer l'image</label>
                            <div class="col-sm-7">
                                <div class="list-group">
                                    <div class="list-group-item">{!! Form::file('file') !!}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <input type="hidden" value="{{ $seminaire->id }}" name="id">
                        <button type="submit" class="btn btn-info">Envoyer</button>
                    </div>
                </form>

            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-info">
                <div class="panel-body">
                    <div id="listBlocs">
                        <h4><i class="fa fa-list"></i> &nbsp;Contributions au séminaire</h4>
                        @if(!$seminaire->subjects->isEmpty())
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                @foreach($seminaire->subjects as $subject)
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab">
                                            <h4 class="panel-title">
                                                <a class="collapsed pull-left" data-toggle="collapse" data-parent="#accordion" href="#edit_subject_{{ $subject->id }}">
                                                    <strong>{!! $subject->title !!}</strong>
                                                </a>
                                                <form action="{{ url('admin/subject/'.$subject->id) }}" method="POST" class="subject-delete">
                                                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                    <button data-what="Supprimer" data-action="{{ $subject->title }}" class="btn btn-danger btn-sm btn-xs deleteAction"><i class="fa fa-times"></i></button>
                                                </form>
                                            </h4>
                                        </div>
                                        <div id="edit_subject_{{ $subject->id }}" class="panel-collapse collapse">
                                            <div class="panel-body panel-noborder">
                                                @include('backend.seminaires.partials.subject-edit', ['subject' => $subject, 'seminaire' => $seminaire])
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <hr/>
                    <a data-toggle="collapse" href="#add_subject" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> &nbsp;Ajouter contribution</a>
                    <div class="well collapse subject_content" id="add_subject">
                        <?php $max = $seminaire->subjects->max('rang'); ?>
                        @include('backend.seminaires.partials.subject-create', ['max' => $max, 'seminaire' => $seminaire])
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop