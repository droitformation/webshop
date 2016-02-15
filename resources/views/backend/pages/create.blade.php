@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/pages/'.$site) }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des pages</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/page') }}" method="POST" class="form-horizontal" >
            {!! csrf_field() !!}

            <div class="panel-heading"><h4>Ajouter une</h4></div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Visible sur le site</label>
                        <div class="col-sm-5">
                            <label class="radio-inline"><input type="radio" value="0" name="hidden"> Oui</label>
                            <label class="radio-inline"><input type="radio" value="1" name="hidden" checked> Non</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre dans le menu</label>
                        <div class="col-sm-2">
                            {!! Form::text('menu_title', null , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Site</label>
                        <div class="col-sm-2">
                            @if(!$sites->isEmpty())
                                <select class="form-control" name="site_id">
                                    <option value="">Appartient au site</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}">{{ $site->nom }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Menu</label>
                        <div class="col-sm-5">
                            @if(!$menus->isEmpty())
                                <?php $menu_sites = $menus->groupBy('site_id'); ?>
                                <select class="form-control" name="menu_id">
                                    @foreach($menu_sites as $site_id => $menu_site)
                                        <optgroup label="{{ $sites->find($site_id)->nom }}">
                                            @foreach($menu_site as $menu)
                                                <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Type de contenu</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="template">
                                <option value="page">Page</option>
                                <option value="index">Page d'accueil</option>
                                <option value="newsletter">Contenu généré newsletter</option>
                                <option value="jurisprudence">Contenu généré jurisprudence</option>
                                <option value="doctrine">Contenu généré doctrine</option>
                                <option value="revue">Contenu généré revues</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Ordre dans le menu</label>
                        <div class="col-sm-1">
                            {!! Form::text('rang', null , array('class' => 'form-control') ) !!}
                        </div>
                        <div class="col-sm-2">
                            <p class="help-block">Ordre croissant</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-7">
                            {!! Form::text('title', null , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Contenu</label>
                        <div class="col-sm-7">
                            {!! Form::textarea('content', null, array('class' => 'form-control  redactor' )) !!}
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3">
                        {!! Form::hidden('parent_id', 0) !!}
                        <input type="hidden" name="template" value="page">
                    </div>
                    <div class="col-sm-6">
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>
<!-- end row -->

@stop