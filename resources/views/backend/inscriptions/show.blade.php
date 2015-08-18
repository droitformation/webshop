@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/page') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if (!empty($page) )

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/page/'.$page->id) }}" method="POST" class="form-horizontal" >
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter {{ $page->titre }}</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">Hiérarchie</label>
                        <div class="col-sm-4">

                            <select class="form-control" name="parent_id">
                                <option {{ $page->parent_id == 0 ? 'checked' : '' }} value="0">Base</option>
                                @if(!empty($parents))
                                    @foreach($parents as $parent)
                                        <option {{ $page->isDescendantOf($parent) ? 'selected' : '' }} value="{{ $parent->id }}">
                                            {{ $parent->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <?php
                      //     echo '<pre>';
                           // print_r($parents);
                            //echo '</pre>';
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-4">
                            {!! Form::text('title', $page->title , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Auteur</label>
                        <div class="col-sm-4">
                            {!! Form::text('auteur', $page->auteur , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Ouvrage</label>
                        <div class="col-sm-2">
                            {!! Form::text('ouvrage', $page->ouvrage , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Page</label>
                        <div class="col-sm-2">
                            {!! Form::text('page', $page->page , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

{{--                    <div class="form-group">
                        <label for="paragraphe" class="col-sm-3 control-label">Paragraphe</label>
                        <div class="col-sm-7">
                            {!! Form::textarea('paragraphe', $page->paragraphe , array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                        </div>
                    </div>--}}

                    <div class="form-group">
                        <label for="content" class="col-sm-3 control-label">Contenu</label>
                        <div class="col-sm-7">
                            {!! Form::textarea('content', $page->content , array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                        </div>
                    </div>
                </div>
                <div class="panel-footer mini-footer ">
                    {!! Form::hidden('id', $page->id ) !!}
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <button class="btn btn-primary" type="submit">Envoyer </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @endif

</div>
<!-- end row -->

@stop