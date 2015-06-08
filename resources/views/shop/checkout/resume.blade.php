@extends('layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <h2>Resumé de la commande</h2>
    </div>
</div>

<!-- Cart  -->
@include('partials.cart')

<div class="row" id="livraison">

    <div class="col-md-6">
        <h4>Adresse de livraison</h4>

        <?php $user->adresse_livraison->load(['pays','civilite']); ?>
        <address>
            <strong>{{ $user->adresse_livraison->civilite->title }} {{ $user->adresse_livraison->first_name }} {{ $user->adresse_livraison->last_name }}</strong><br>
            {!! !empty($user->adresse_livraison->company) ? $user->adresse_livraison->company.'<br>' : '' !!}
            {{ $user->adresse_livraison->adresse }}<br>
            {!! !empty($user->adresse_livraison->complement) ? $user->adresse_livraison->complement.'<br>' : '' !!}
            {!! !empty($user->adresse_livraison->cp) ? $user->adresse_livraison->cp.'<br>' : '' !!}
            {{ $user->adresse_livraison->npa }} {{ $user->adresse_livraison->ville }}<br>
            {{ $user->adresse_livraison->pays->title }}
        </address>
        <p><a data-toggle="modal" data-target="#myModal" href="#">Modifier votre adresse</a></p>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Modifier votre adresse</h4>
                </div>
                <div class="modal-body" id="updateAdresse">

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-7">
                            <input type="text" name="email" class="form-control" value="{{ $user->adresse_livraison->email }}" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Titre</label>
                        <div class="col-sm-7">
                            <label class="radio-inline">
                                <input type="radio" name="civilite" value="1"> Monsieur
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="civilite" value="2"> Madame
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="civilite" value="3"> Me
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Prénom</label>
                        <div class="col-sm-7">
                            <input type="text" name="first_name" class="form-control" value="{{ $user->adresse_livraison->first_name }}" placeholder="Prénom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Nom</label>
                        <div class="col-sm-7">
                            <input type="text" name="last_name" class="form-control" value="{{ $user->adresse_livraison->last_name }}" placeholder="Nom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Profession</label>
                        <div class="col-sm-7">
                            <input type="text" name="profession_id" class="form-control" value="{{ $user->adresse_livraison->profession_id }}" placeholder="Profession">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Entreprise</label>
                        <div class="col-sm-7">
                            <input type="text" name="company" class="form-control" value="{{ $user->adresse_livraison->company }}" placeholder="Entreprise">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Adresse</label>
                        <div class="col-sm-7">
                            <input type="text" name="adresse" class="form-control" value="{{ $user->adresse_livraison->adresse }}" placeholder="Adresse">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Complément d'adresse</label>
                        <div class="col-sm-7">
                            <input type="text" name="complement" class="form-control" value="{{ $user->adresse_livraison->complement }}" placeholder="Complément d'adresse">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Case Postale</label>
                        <div class="col-sm-7">
                            <input type="text" name="cp" class="form-control" value="{{ $user->adresse_livraison->cp }}" placeholder="Case Postale">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Code postal</label>
                        <div class="col-sm-7">
                            <input type="text" name="npa" class="form-control" value="{{ $user->adresse_livraison->npa }}" placeholder="Code postal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Localité</label>
                        <div class="col-sm-7">
                            <input type="text" name="ville" class="form-control" value="{{ $user->adresse_livraison->ville }}" placeholder="Localité">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Canton</label>
                        <div class="col-sm-7">
                            <input type="text" name="canton_id" class="form-control" value="{{ $user->adresse_livraison->canton_id }}" placeholder="Canton">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Pays</label>
                        <div class="col-sm-7">
                            <input type="text" name="pays_id" class="form-control"  value="{{ $user->adresse_livraison->pays_id }}" placeholder="Pays">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Téléphone</label>
                        <div class="col-sm-7">
                            <input type="text" name="telephone" class="form-control" placeholder="Téléphone">
                        </div>
                    </div>
      
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">fermer</button>
                    <button type="button" class="btn btn-primary">Sauver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<nav>
    <ul class="pager">
        <li class="previous"><a href="{{ url('/') }}"><span aria-hidden="true">&larr;</span> Retour au shop</a></li>
        <li class="next next-commander"><a href="{{ url('checkout/billing') }}">Finaliser ma commande <span aria-hidden="true">&rarr;</span></a></li>
    </ul>
</nav>

@stop