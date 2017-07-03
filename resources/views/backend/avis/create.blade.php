@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/avis')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{{ url('admin/avis') }}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

                <div class="panel panel-midnightblue">
                    <div class="panel-body">

                        <h3>Question</h3>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Type</label>
                            <div class="col-sm-5">
                                <select name="type" class="form-control" id="sondageTypeSelect">
                                    <option value="text">Texte</option>
                                    <option value="checkbox">Case à cocher</option>
                                    <option value="radio">Options à choix</option>
									<option value="chapitre">Chapitre</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Question</label>
                            <div class="col-sm-5">
                                <textarea name="question" required class="form-control redactorSimple"></textarea>
                            </div>
                        </div>

                        <div class="form-group" id="sondageChoices" style="display: none;">
                            <label for="message" class="col-sm-3 control-label">Choix (séparés par virgules)</label>
                            <div class="col-sm-4">
                                <textarea id="textareaSondageChoices" style="height: 80px;" name="choices" class="form-control"></textarea>
                            </div>
                            <div class="col-md-5">
                                @if(!$examples->isEmpty())
                                    <h5>Exemples déjà crées, cliquer pour reprendre</h5>
                                    @foreach($examples as $example)
                                        <p><button class="btn btn-default btn-sm choice-example">{{ $example }}</button></p>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer mini-footer ">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <button class="btn btn-primary" type="submit">Envoyer</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>
<!-- end row -->

@stop