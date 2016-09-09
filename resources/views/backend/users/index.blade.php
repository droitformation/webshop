@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">

        <h3>Recherche</h3>

        <div class="options text-right" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/user/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a>
            </div>
        </div>

         <div class="row">

             <div class="col-md-3">
                 <div class="panel panel-midnightblue">
                     <div class="panel-body">
                         <h4>Rechercher</h4>
                         <form action="{{ url('admin/search/user') }}" method="post">{!! csrf_field() !!}
                             <div class="input-group">
                                 <input type="text" class="form-control" name="term" placeholder="Recherche...">
                                 <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                                 </span>
                             </div><!-- /input-group -->
                         </form>
                     </div>
                 </div>
             </div>

             <div class="col-md-9">
                 <div class="panel panel-midnightblue">
                     <div class="panel-body">
                         @if(isset($users) && !$users->isEmpty())
                         <h4>Dernier comptes crées</h4>
                             <table class="table">
                                 <thead>
                                 <tr>
                                     <th class="col-sm-1">Action</th>
                                     <th class="col-sm-3">Nom</th>
                                     <th class="col-sm-3">Email</th>
                                     <th class="col-sm-2"></th>
                                 </tr>
                                 </thead>
                                 <tbody class="selects">
                                     @foreach($users as $user)
                                         <tr>
                                             <td><a class="btn btn-sky btn-sm" href="{{ url('admin/user/'.$user->id) }}">&Eacute;diter</a></td>
                                             <td><strong>{{ $user->name }}</strong></td>
                                             <td>{{ $user->email }}</td>
                                             <td class="text-right">
                                                 <form action="{{ url('admin/user/'.$user->id) }}" method="POST" class="form-horizontal">
                                                     <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                     <button data-what="Supprimer" data-action="{{ $user->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                                 </form>
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
                         @endif

                         @if(isset($adresses) && !$adresses->isEmpty())
                             <h4>Dernières adresses crées</h4>
                             <table class="table" style="margin-bottom: 0px;" >
                                 <thead>
                                 <tr>
                                     <th class="col-sm-1">Action</th>
                                     <th class="col-sm-3">Nom</th>
                                     <th class="col-sm-3">Email</th>
                                     <th class="col-sm-2">Entreprise</th>
                                     <th class="col-sm-2">Ville</th>
                                 </tr>
                                 </thead>
                                 <tbody class="selects">

                                     @foreach($adresses as $adresse)
                                         <tr>
                                             <td><a class="btn btn-sky btn-sm" href="{{ url('admin/adresse/'.$adresse->id) }}">&Eacute;diter</a></td>
                                             <td><strong>{{ $adresse->name }}</strong></td>
                                             <td>{{ $adresse->email }}</td>
                                             <td>{{ $adresse->company }}</td>
                                             <td>{{ $adresse->ville }}</td>
                                             <td class="text-right">
                                                 <form action="{{ url('admin/adresse/'.$adresse->id) }}" method="POST" class="form-horizontal">
                                                     <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                                     <button data-what="Supprimer" data-action="{{ $adresse->name }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                                 </form>
                                             </td>
                                         </tr>
                                     @endforeach

                                 </tbody>
                             </table>
                         @endif
                     </div>
                 </div>
             </div>

         </div>

    </div>
</div>

@stop