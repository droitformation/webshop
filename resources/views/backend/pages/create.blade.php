@extends('backend.layouts.master')
@section('content')


<!-- start row -->
<div class="row">
    <div class="col-md-12">
        <p><a class="btn btn-default" href="{{ url('admin/pages/'.$current_site) }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste des pages</a></p>
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/page') }}" method="POST" class="form-horizontal" >{!! csrf_field() !!}

                <div class="panel-body event-info">
                    <h4>Ajouter une page</h4>

                    <div class="row">
                        <h4 class="col-sm-4">Général</h4>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Visible sur le site</label>
                        <div class="col-sm-5">
                            <label class="radio-inline"><input type="radio" value="" name="hidden"> Oui</label>
                            <label class="radio-inline"><input type="radio" value="1" name="hidden" checked> Non</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre dans le menu</label>
                        <div class="col-sm-2">
                            <input type="text" name="menu_title" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Site</label>
                        <div class="col-sm-2">
                            @if(!$sites->isEmpty())
                                <select class="form-control" name="site_id">
                                    <option value="">Appartient au site</option>
                                    @foreach($sites as $site)
                                        <option {{ $site->id == $current_site ? 'selected' : '' }} value="{{ $site->id }}">{{ $site->nom }}</option>
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
                                    <option value="">Choix</option>
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
                        <label for="message" class="col-sm-3 control-label">Ordre dans le menu</label>
                        <div class="col-sm-1">
                            <input type="text" name="rang" value="{{ old('rang') }}" class="form-control">
                        </div>
                        <div class="col-sm-2"><p class="help-block">Ordre croissant</p></div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Lien slug</label>
                        <div class="col-sm-3">
                            <input type="text" name="slug" value="{{ old('slug') }}" class="form-control">
                        </div>
                        <div class="col-sm-2"><p class="help-block">Pour webmaster</p></div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Type de contenu</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="template">
                                <option selected value="page">Page</option>
                                <option value="index">Page d'accueil</option>
                                <option value="contact">Page de contact</option>
                                <option value="newsletter">Contenu généré newsletter</option>
                                <option value="jurisprudence">Contenu généré jurisprudence</option>
                                <option value="doctrine">Contenu généré doctrine</option>
                                <option value="authors">Contenu généré auteurs</option>
                                <option value="colloques">Contenu généré colloques</option>
                                <option value="revue">Contenu généré revues</option>
                            </select>
                        </div>
                    </div>

                    <hr/>

                    <div class="row">
                        <h4 class="col-sm-4">Contenus</h4>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre</label>
                        <div class="col-sm-7">
                            <input type="text" name="title" value="{{ old('title') }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contenu" class="col-sm-3 control-label">Résumé</label>
                        <div class="col-sm-7">
                            <textarea name="excerpt" class="form-control redactorSimple">{{ old('excerpt') }}</textarea>
                        </div>
                    </div>

                    @if($current_site == 4 || $current_site == 5)
                        <div class="form-group">
                            <label for="contenu" class="col-sm-3 control-label">Contenu</label>
                            <div class="col-sm-7">
                                <textarea name="content" class="form-control redactor">{{ old('content') }}</textarea>
                            </div>
                        </div>
                    @endif

                    <div class="well">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ceci est un lien externe</label>
                            <div class="col-sm-5">
                                <label class="radio-inline"><input type="radio" value="1" name="isExternal"> Oui</label>
                                <label class="radio-inline"><input type="radio" value="0" name="isExternal" checked> Non</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contenu" class="col-sm-3 control-label">Lien</label>
                            <div class="col-sm-7">
                                <input type="text" name="url" value="{{ old('url') }}" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3">
                        <input type="hidden" name="parent_id" value="0">
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