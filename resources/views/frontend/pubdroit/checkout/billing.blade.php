@extends('frontend.pubdroit.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="heading-bar">
            <h2>1. Livraison</h2>
            <span class="h-line"></span>
        </div>

        <section class="checkout-holder">
            <div class="row">
                <div class="col-md-8">
                    <div class="r-title-bar title-left"><strong>Adresse de livraison</strong></div>
                    <form class="form" method="post" action="{{ url('checkout/confirm') }}" id="billing">
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
                                    <select disabled name="canton_id" class="form-control">
                                        <option value="208">Suisse</option>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <div class="form-group">
                                    <div class="col-md-12">

                                        <input name="type" value="1" type="hidden">
                                        <input name="livraison" value="1" type="hidden">
                                        <input name="user_id" value="{{ $user->id }}" type="hidden">

                                        <cite class="green-t">* Champs requis</cite>
                                        <button type="submit" class="more-btn">Continuer</button>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="side-holder">
                        <div class="r-title-bar title-right"> <strong><a href="#">Liens utiles</a></strong></div>
                        <ul class="review-list">
                            <li><a href="{{ url('livraison') }}">Livraison&nbsp;<i class="fa fa-truck pull-right"></i></a></li>
                            <li><a href="{{ url('conditions') }}">Conditions générales&nbsp;<i class="fa fa-info pull-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

@stop