@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p class="pull-left"><a class="btn btn-default" href="{{ url('admin/pages/'.$page->site_id) }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
        <p class="pull-right"><a href="{{ url('admin/page/create/'.$page->site_id) }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter une page</a></p>
    </div>
</div>

<!-- start row -->
<div class="row">

    @if (!empty($page) )

    <?php $template = ($page->template == 'page' || $page->template == 'index' ? true : false); ?>

    <div class="col-md-{{ $template ? 7 : 12 }}">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form data-validate-parsley action="{{ url('admin/page/'.$page->id) }}" method="POST" class="form-horizontal" >
                <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

                <div class="panel-body event-info">
                    <h4>&Eacute;diter {{ $page->titre }}</h4>
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
                            <label class="radio-inline"><input type="radio" value="" {{ !$page->hidden ? 'checked' : '' }} name="hidden"> Oui</label>
                            <label class="radio-inline"><input type="radio" value="1" {{ $page->hidden ? 'checked' : '' }} name="hidden"> Non</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Titre dans le menu</label>
                        <div class="col-sm-5">
                            {!! Form::text('menu_title', $page->menu_title , array('class' => 'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Lien slug</label>
                        <div class="col-sm-3">
                            <input type="text" name="slug" value="{{ $page->slug }}" class="form-control">
                        </div>
                        <div class="col-sm-2"><p class="help-block">Pour webmaster</p></div>
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

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Menu</label>
                        <div class="col-sm-5">
                            @if(!$menus->isEmpty())
                                <select class="form-control" name="menu_id">
                                    <option value="">Choix</option>
                                    @foreach($menus as $menu)
                                        <option {{ $page->menu_id == $menu->id ? 'selected' : '' }} value="{{ $menu->id }}">{{ $menu->title }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <hr/>

                    @if(!$page->isExternal)

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Type de contenu</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="template">
                                    <option {{ $page->template == 'page' ? 'selected' : '' }} value="page">Page</option>
                                    <option {{ $page->template == 'index' ? 'selected' : '' }} value="index">Page d'accueil</option>
                                    <option {{ $page->template == 'contact' ? 'selected' : '' }} value="contact">Page de contact</option>
                                    <option {{ $page->template == 'newsletter' ? 'selected' : '' }} value="newsletter">Contenu généré newsletter</option>
                                    <option {{ $page->template == 'jurisprudence' ? 'selected' : '' }} value="jurisprudence">Contenu généré jurisprudence</option>
                                    <option {{ $page->template == 'doctrine' ? 'selected' : '' }} value="doctrine">Contenu généré doctrine</option>
                                    <option {{ $page->template == 'authors' ? 'selected' : '' }} value="authors">Contenu généré auteurs</option>
                                    <option {{ $page->template == 'colloques' ? 'selected' : '' }} value="colloques">Contenu généré colloques</option>
                                    <option {{ $page->template == 'revue' ? 'selected' : '' }} value="revue">Contenu généré revues</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message" class="col-sm-3 control-label">Titre</label>
                            <div class="col-sm-8">
                                {!! Form::text('title', $page->title , array('class' => 'form-control') ) !!}
                            </div>
                        </div>

                        @if(($current_site == 4 || $current_site == 5) && in_array($page->template,['index','page','contact']))
                            <div class="form-group">
                                <label for="contenu" class="col-sm-3 control-label">Résumé</label>
                                <div class="col-sm-8">
                                    <textarea name="excerpt" class="form-control redactorSimple">{{ $page->excerpt }}</textarea>
                                </div>
                            </div>
                        @endif
                        @if(in_array($page->template,['index','page','contact','revue']))
                            <div class="form-group">
                                <label for="contenu" class="col-sm-3 control-label">Contenu</label>
                                <div class="col-sm-8">
                                    {!! Form::textarea('content', $page->content , ['class' => 'form-control  redactor']) !!}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="well">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Ceci est un lien externe</label>
                                <div class="col-sm-5">
                                    <label class="radio-inline">
                                        <input type="radio" value="1" {{ $page->isExternal ? 'checked' : '' }} name="isExternal"> Oui
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" value="0" {{ !$page->isExternal ? 'checked' : '' }} name="isExternal"> Non
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="contenu" class="col-sm-3 control-label">Lien</label>
                                <div class="col-sm-7">
                                    {!! Form::text('url', $page->url, array('class' => 'form-control' )) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3">
                        {!! Form::hidden('parent_id', 0) !!}
                        {!! Form::hidden('id', $page->id ) !!}
                    </div>
                    <div class="col-sm-9">
                        <button class="btn btn-primary" type="submit">Envoyer </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @if($template && !$page->isExternal)
        <div class="col-md-5">
            <div class="panel panel-midnightblue"  id="appComponent">
                <div class="panel-body event-info">

                    <div class="form-group">
                        <h4>Blocs de contenu</h4>

                        @if(!$page->contents->isEmpty())

                            @include('backend.pages.partials.list', ['page' => $page])

                            @foreach($page->contents as $pagecontent)
                                <div class="collapse" id="pagecontent_{{ $pagecontent->id }}">
                                    <div class="well well-sm">
                                        @include('backend.pages.partials.edit', ['content' => $pagecontent])
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <hr/>
                    <div class="form-group">
                        <h4>Ajouter un bloc de contenu</h4>

                        <div id="adresseParent">
                            <div class="btn-group" style="margin-bottom: 15px;">
                                @foreach($types as $name => $type)
                                    <a class="btn btn-sm btn-{{ $type['color'] }} accordion-toggle" data-toggle="bloc{{ $name }}"><i class="fa fa-plus"></i> &nbsp;Bloc {{ $type['title'] }}</a>
                                @endforeach
                            </div>
                            @foreach($types as $name => $type)
                               <div class="collapse" id="bloc{{ $name }}">
                                   <form data-validate-parsley class="form" method="post" action="{{ url('admin/pagecontent') }}">
                                       {!! csrf_field() !!}
                                       <div class="well well-sm">
                                           @include('backend.pages.create.'.$name, ['page_id' => $page->id])
                                       </div>
                                   </form>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>

        </div>
        @endif
    @endif

</div>
<!-- end row -->

@stop