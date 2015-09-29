@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="options text-left" style="margin-bottom: 10px;">
                <div class="btn-toolbar">
                    <a href="{{ url('admin/colloque') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> &nbsp;Retour</a>
                </div>
            </div>

            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Ajouter un colloque</h4>
                </div>
                <div class="panel-body">
                    <form action="{{ url('admin/colloque') }}" method="POST" class="form-horizontal" id="wizard">
                        {!! csrf_field() !!}

                        <fieldset title="Step 1">
                            <legend>Général</legend>

                            <div class="form-group">
                                <label for="titre" class="col-sm-3 control-label">Titre</label>
                                <div class="col-sm-6">
                                    {!! Form::text('titre', '' , array('class' => 'form-control required' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block">Requis</p></div>
                            </div>

                            <div class="form-group">
                                <label for="soustitre" class="col-sm-3 control-label">Sous-titre</label>
                                <div class="col-sm-6">
                                    {!! Form::text('soustitre', '' , array('class' => 'form-control' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block"></p></div>
                            </div>

                            <div class="form-group">
                                <label for="sujet" class="col-sm-3 control-label">Sujet</label>
                                <div class="col-sm-6">
                                    {!! Form::text('sujet', '' , array('class' => 'form-control required' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block">Requis</p></div>
                            </div>

                            <div class="form-group">
                                <label for="organisateur" class="col-sm-3 control-label">Organisateur</label>
                                <div class="col-sm-6">
                                    {!! Form::text('organisateur', '' , array('class' => 'form-control required' )) !!}
                                </div>
                                <div class="col-sm-3"><p class="help-block">Requis</p></div>
                            </div>

                            <div class="form-group">
                                <label for="organisateur" class="col-sm-3 control-label">Centres</label>
                                <div class="col-sm-6">

                                    @if(!$organisateurs->isEmpty())
                                        @foreach($organisateurs as $organisateur)
                                            <div class="toggleItem">
                                               {{ $organisateur->name }} <br/><input type="checkbox" name="centres[]" value="{{ $organisateur->id }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="endroit" class="col-sm-3 control-label">Endroit</label>
                                <div class="col-sm-6">
                                    {!! Form::text('endroit', '' , array('class' => 'form-control required' )) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Endroit</label>
                                <div class="col-sm-6 col-xs-8">
                                    <select class="form-control" name="endroit" id="endroitSelect">
                                        @if(!$locations->isEmpty())
                                            <option value="">Choix</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 col-xs-8 col-md-push-3 col-xs-push-0">
                                    <div id="showEndroit"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description" class="col-sm-3 control-label">Remarques</label>
                                <div class="col-sm-6">
                                    <textarea name="description" id="description" cols="50" rows="4" class="redactorSimple form-control"></textarea>
                                </div>
                            </div>

                        </fieldset>

                        <fieldset title="Step 2">
                            <legend>Personal Data</legend>
                            <div class="form-group">
                                <label for="fieldnick" class="col-md-3 control-label">Nick</label>
                                <div class="col-md-6"><input id="fieldnick" class="form-control" name="name" minlength="4" type="text" required></div>
                            </div>
                            <div class="form-group">
                                <label for="fieldabout" class="col-md-3 control-label">About</label>
                                <div class="col-md-6"><textarea id="fieldabout" class="form-control autosize" rows="2"></textarea></div>
                            </div>
                        </fieldset>

                        <fieldset title="Step 3">
                            <legend>Preview</legend>
                            <div class="form-group">
                                <label for="" class="col-md-3 control-label">Terms and Conditions</label>
                                <div class="col-md-9">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, nemo, atque consequuntur officiis asperiores consectetur porro labore commodi esse error quisquam nihil illum sunt facere inventore possimus autem ab voluptates quibusdam non voluptatum suscipit architecto. Illo, facilis, corporis, veritatis dolores minus quasi iure cupiditate quis autem ducimus nisi obcaecati tenetur sed ea excepturi pariatur consequatur enim labore officia mollitia. Rerum, voluptatem numquam molestiae recusandae iusto ipsum inventore unde accusantium labore delectus? Doloremque, fugit, sunt libero laboriosam cupiditate sed sequi nostrum saepe. Mollitia, alias, expedita accusantium porro error autem dolore veniam commodi nesciunt provident vitae neque. Nostrum, sed, molestias itaque provident inventore natus animi quasi laborum laboriosam facere ratione aperiam iusto. Non ducimus facere sunt doloribus? Asperiores, natus distinctio quis iure!</p>
                                </div>
                            </div>
                        </fieldset>

                        <input type="submit" class="finish btn-success btn" value="Submit" />
                    </form>
                </div>

            </div>

        </div>
    </div>

@stop