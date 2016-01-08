@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p class="pull-left"><a class="btn btn-default" href="{{ url('admin/page') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
        <p class="pull-right"><a href="{{ url('admin/page/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une page</a></p>
    </div>
</div>

<!-- start row -->
<div class="row">

    @if (!empty($page) )

    <div class="col-md-6">
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
                        <label for="message" class="col-sm-3 control-label">Ordre dans le menu</label>
                        <div class="col-sm-2">
                            {!! Form::text('rang', $page->rang , array('class' => 'form-control') ) !!}
                        </div>
                        <div class="col-sm-4">
                            <p class="help-block">Ordre croissant</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Visible sur le site</label>
                        <div class="col-sm-5">
                            <label class="radio-inline"><input type="radio" value="0" {{ !$page->hidden ? 'checked' : '' }}  name="hidden" checked=""> Oui</label>
                            <label class="radio-inline"><input type="radio" value="1" {{ $page->hidden ? 'checked' : '' }} name="hidden"> Non</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre dans le menu</label>
                        <div class="col-sm-5">
                            {!! Form::text('slug', $page->slug , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Site</label>
                        <div class="col-sm-5">
                            @if(!$sites->isEmpty())
                                <select class="form-control" name="site_id">
                                    <option value="">Appartient au site</option>
                                    @foreach($sites as $site)
                                        <option {{ $page->site_id == $site->id ? 'selected' : '' }} value="{{ $site->id }}">{{ $site->nom }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <hr/>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-8">
                            {!! Form::text('title', $page->title , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Contenu</label>
                        <div class="col-sm-8">
                            {!! Form::textarea('content', $page->content , array('class' => 'form-control  redactor' )) !!}
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    {!! Form::hidden('parent_id', 0) !!}
                    {!! Form::hidden('id', $page->id ) !!}
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <button class="btn btn-primary" type="submit">Envoyer </button>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <div class="col-md-6">
        <div class="panel panel-midnightblue">

            <div class="panel-body event-info">
                <div class="form-group">

                    <h4>Blocs de contenu</h4>

                    <div id="content-bloc-wrapper" data-page="{{ $page->id }}">

                        <a href="#" data-type="lois" class="new-bloc-content btn btn-success btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc lois</a>
                        <a href="#" data-type="autorite" class="new-bloc-content btn btn-magenta btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc autorité</a>
                        <a href="#" data-type="lien" class="new-bloc-content btn btn-orange btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc lien</a>
                        <a href="#" data-type="faq" class="new-bloc-content btn btn-green btn-sm"><i class="fa fa-plus"></i> &nbsp;Bloc FAQ</a>

                        <div id="bloc-wrapper" data-page="{{ $page->id }}">

                        </div>
                    </div>

                    <hr/>

                    @if(!$page->blocs->isEmpty())
                        <?php $grouped = $page->blocs->groupBy('type'); ?>
                        @foreach($grouped as $groupe => $blocs)
                            <h5>{{ ucfirst($groupe) }}</h5>
                            <ul class="list-group">
                                @foreach($blocs as $bloc)
                                    <li class="list-group-item">{!! $bloc->name !!}</li>
                                @endforeach
                            </ul>
                        @endforeach
                    @endif

                </div>
            </div>

        </div>
    </div>

    @endif

</div>
<!-- end row -->

@stop