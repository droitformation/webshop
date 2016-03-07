@extends('frontend.pubdroit.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <p><a href="{{ url('checkout/cart') }}"><span aria-hidden="true">&larr;</span> Retour au panier</a></p>

        <div class="row">
            <div class="col-md-8">

                <div class="heading-bar">
                    <h2>2. Livraison</h2>
                    <span class="h-line"></span>
                </div>

                <form class="form" method="post" action="{{ url('checkout/resume') }}" id="billing">
                    {!! csrf_field() !!}
                    <ul class="billing-form">

                        <!-- if adresse exist -->
                        <?php $adresse = $user->adresse_livraison ? $user->adresse_livraison : null; ?>

                        <li class="form-group">
                            <div class="col-md-6">
                                <label class="control-label" for="">Titre</label>
                                <select name="civilite_id" class="form-control">
                                    <option {{ $adresse && $adresse->civilite_id == 4 ? 'selected' : '' }} value="4"></option>
                                    <option {{ $adresse && $adresse->civilite_id == 1 ? 'selected' : '' }} value="1">Monsieur</option>
                                    <option {{ $adresse && $adresse->civilite_id == 2 ? 'selected' : '' }} value="2">Madame</option>
                                    <option {{ $adresse && $adresse->civilite_id == 3 ? 'selected' : '' }} value="3">Me</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="">Email <sup>*</sup></label>
                                <input class="form-control"  name="email" id="email" value="{{ $user->email }}" type="text">
                            </div>
                        </li>
                        <li class="form-group">
                            <div class="col-md-6">
                                <label class="control-label" for="">Prénom <sup>*</sup></label>
                                <input class="form-control"  name="first_name" id="first_name" value="{{ $user->first_name }}" type="text">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="">Nom <sup>*</sup></label>
                                <input class="form-control"  name="last_name" id="last_name" value="{{ $user->last_name }}" type="text">
                            </div>
                        </li>
                        <li class="form-group">
                            <div class="col-md-6">
                                <label class="control-label" for="">Entreprise</label>
                                <input class="form-control" name="company" value="{{ $adresse ? $adresse->company : '' }}" type="text">
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="">Profession</label>
                                <select name="canton_id" class="form-control">
                                    <option value="">Choix</option>
                                    @foreach($professions as $profession)
                                        <option value="{{ $profession->id }}">{{ $profession->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </li>

                        <li class="form-group">
                            <div class="col-md-12">
                                <label class="control-label" for="">Adresse <sup>*</sup></label>
                                <input class="form-control" name="adresse" id="adresse" value="{{ $adresse ? $adresse->adresse : '' }}" type="text">
                            </div>
                        </li>
                        <li class="form-group">
                            <div class="col-md-4">
                                <label class="control-label" for="">CP</label>
                                <input class="form-control" name="cp" value="{{ $adresse ? $adresse->cp : '' }}" type="text">
                            </div>
                            <div class="col-md-8">
                                <label class="control-label" for="">Complément d'adresse</label>
                                <input class="form-control" name="complement" value="{{ $adresse ? $adresse->complement : '' }}" type="text">
                            </div>
                        </li>
                        <li class="form-group">
                            <div class="col-md-4">
                                <label class="control-label" for="">NPA <sup>*</sup></label>
                                <input class="form-control" id="npa" name="npa" value="{{ $adresse ? $adresse->npa : '' }}" type="text">
                            </div>
                            <div class="col-md-8">
                                <label class="control-label" for="">Ville <sup>*</sup></label>
                                <input class="form-control" id="ville" name="ville" value="{{ $adresse ? $adresse->ville : '' }}" type="text">
                            </div>
                        </li>
                        <li class="form-group">
                            <div class="col-md-6">
                                <label class="control-label" for="">Canton</label>
                                <select name="canton_id" class="form-control">
                                    <option value="">Choix</option>
                                    @foreach($cantons as $canton)
                                        <option value="{{ $canton->id }}">{{ $canton->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="control-label" for="">Pays</label>
                                <select disabled name="pays_id" class="form-control">
                                    <option value="208">Suisse</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <hr/>
                                    <input name="type" value="1" type="hidden">
                                    <input name="livraison" value="1" type="hidden">
                                    <input name="user_id" value="{{ $user->id }}" type="hidden">
                                    <input name="pays_id" value="208" type="hidden">

                                    {!! $adresse ? '<input type="hidden" name="id" value="'.$adresse->id.'">' : '' !!}

                                    <cite class="text-danger"><small>* Champs requis</small></cite>
                                    <button type="submit" class="more-btn">Continuer &nbsp;<i class="fa fa-arrow-circle-right"></i></button>
                                </div>
                            </div>
                        </li>

                    </ul>
                </form>
            </div>
            <div class="col-md-4">

                <div class="heading-bar">
                    <h2>Conditions générales</h2>
                    <span class="h-line"></span>
                </div>

                <div class="side-holder">

                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Généralités</a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <p>Vous vous engagez à fournir des informations sincères et véritables vous concernant.</p>

                                    <p>Publications-droit.ch peut apporter toutes modifications qu’il jugera nécessaire à ce service.
                                        Les conditions générales de vente seront alors celles en vigueur sur le site à la date de la confirmation de commande.
                                        Nous vous invitons donc à revenir régulièrement pour vous tenir au courant des évolutions du service.
                                        Sauf preuve contraire, les données enregistrées par publications-droit.ch constituent la preuve de l’ensemble des transactions
                                        passées par publications-droit.ch et ses clients.</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Remboursement</a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                <div class="panel-body">
                                    <p>Les achats de livres sont fermes et définitifs. Ils ne pourront donc donner lieu à échange, remboursement ou à l’exercice d’un droit de
                                        rétractation.Livraison et disponibilitéLes expéditions se font du lundi au vendredi inclus (sauf jours fériés) dans la mesure des
                                        disponibilités de l’ouvrage. Un e-mail de confirmation vous est envoyé pour certifier la bonne réception de votre commande.</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Validation</a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                <div class="panel-body">
                                    <p>Vous déclarez avoir pris connaissance et accepté les conditions de la commande et notamment les informations sur les caractéristiques
                                        des produits ou services commandés, le prix, les délais, la livraison, ainsi que les présentes Conditions Générales avant la passation
                                        de votre commande. Vous déclarez avoir la pleine capacité juridique pour passer une commande et accepter les présentes conditions générales.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@stop