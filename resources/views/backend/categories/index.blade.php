@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-6">
        <h3>Catégories</h3>
    </div>
    <div class="col-md-6">
        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
               <a href="{{ url('admin/categorie/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tab-container">
            <ul class="nav nav-tabs">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <li class="{{ $site->id == 1 ? 'active' : '' }}">
                            <a data-toggle="tab" href="#site{{ $site->id }}"><img height="25px" src="{{ asset('logos/'.$site->logo) }}" alt="{{ $site->nom }}" /></a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="tab-content">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <div id="site{{ $site->id }}" class="tab-pane {{ $site->id == 1 ? 'active' : '' }}">
                            <table class="table simple" style="margin-bottom: 0px;">
                                <thead>
                                <tr>
                                    <th class="col-sm-2">Action</th>
                                    <th class="col-sm-3">Images</th>
                                    <th class="col-sm-3">Titre</th>
                                    <th class="col-sm-3">Site</th>
                                    <th class="col-sm-2 no-sort"></th>
                                </tr>
                                </thead>
                                <tbody class="selects">
                                    <?php $categorie_sites = $categories->groupBy('site_id'); ?>
                                    @if(isset($categorie_sites[$site->id]))
                                        @foreach($categorie_sites[$site->id] as $categorie)
                                            <tr>
                                                <td><a class="btn btn-sky btn-sm" href="{{ url('admin/categorie/'.$categorie->id) }}">&Eacute;diter</a></td>
                                                <td><img height="50" src="{!! asset('files/pictos/'.$categorie->site->slug.'/'.$categorie->image) !!}" alt="{{ $categorie->title }}" /></td>
                                                <td><strong>{{ $categorie->title }}</strong></td>
                                                <td>{{ $categorie->site_id }}</td>
                                                <td class="text-right">
                                                    {!! Form::open(array('id' => 'deleteCategorieForm_'.$categorie->id, 'route' => array('admin.categorie.destroy', $categorie->id), 'method' => 'delete')) !!}
                                                    {!! Form::close() !!}
                                                    <button data-id="{{ $categorie->id }}" class="btn btn-danger btn-sm deleteCategorie">Supprimer</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteCategorie" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Annuler</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Suppression de la catégorie</h4>
                    </div>
                    <div class="modal-body">
                        <p>&Ecirc;tes-vous sûr de vouloir supprimer cette catégorie?</p>
                        <div id="modalCategorie"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <button type="button" id="deleteConfirm" class="btn btn-danger">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@stop