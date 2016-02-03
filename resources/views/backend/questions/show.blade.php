@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/faq') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ($question)

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/question/'.$question->id) }}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter question</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Rang</label>
                        <div class="col-sm-2">
                            {!! Form::text('rang', $question->rang , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-6">
                            {!! Form::text('title', $question->title  , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Question</label>
                        <div class="col-sm-6">
                            {!! Form::textarea('question', $question->question , array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Réponse</label>
                        <div class="col-sm-6">
                            {!! Form::textarea('answer', $question->answer , array('class' => 'form-control  redactor', 'cols' => '50' , 'rows' => '4' )) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Catégories</label>
                        <div class="col-sm-4 col-xs-8">
                            <?php $question_categories = $question->categories->pluck('id')->all(); ?>
                            @if(!$categories->isEmpty())
                                <select name="categorie_id[]" class="form-control" multiple>
                                    @foreach($categories as $categorie)
                                        <option {{ in_array($categorie->id ,$question_categories) ? 'selected' : '' }} value="{{ $categorie->id }}">{{ $categorie->title }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                </div>
            <div class="panel-footer mini-footer ">
                {!! Form::hidden('id', $question->id ) !!}
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