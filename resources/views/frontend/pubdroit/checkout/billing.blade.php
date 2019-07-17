@extends('frontend.pubdroit.layouts.master')
@section('content')

    <div class="row" id="appVue">
        <div class="col-md-12">

            <p class="backBtn"><a class="btn btn-sm btn-default btn-profile" href="{{ url('pubdroit/checkout/cart') }}"><span aria-hidden="true">&larr;</span> Retour au panier</a></p>

            <div class="row">
                <div class="col-md-8">

                    <div class="heading-bar">
                        <h2>3. Adresse de facturation</h2>
                        <span class="h-line"></span>
                    </div>

                    <?php $adresse_livraison   = $user->adresse_livraison ? $user->adresse_livraison : null; ?>
                    <?php $adresse_facturation = $user->adresse_facturation ? $user->adresse_facturation : null; ?>

                    <form class="form" method="post" action="{{ url('pubdroit/checkout/resume') }}" id="billing">{!! csrf_field() !!}
                        <div class="adresse-verify">
                            <address id="userFacturation">
                                <adresse-update
                                        :main="{{ $adresse_livraison }}"
                                        btn="btn-sm btn-info"
                                        texte="Changer"
                                        dir="left"
                                        :original="{{ $adresse_facturation }}"
                                        title="Vérifiez l'adresse de facturation"
                                        type="4"></adresse-update>
                            </address>

                            <div>
                                <div class="form-group">
                                    <label class="control-label" for="reference_no">N° référence</label>
                                    <input class="form-control" name="reference_no" value="{{ session()->has('reference_no') ? session()->get('reference_no') : '' }}" id="reference_no" type="text" placeholder="Optionnel">
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="transaction_no">N° commande</label>
                                    <input class="form-control" name="transaction_no" value="{{ session()->has('transaction_no') ? session()->get('transaction_no') : '' }}" id="transaction_no" type="text" placeholder="Optionnel">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="more-btn" id="btn-next-confirm">Continuer &nbsp;<i class="fa fa-arrow-circle-right"></i></button>
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
                                            rétractation.</p>
                                        <p><strong>Livraison et disponibilité</strong></p>
                                        <p>Les expéditions se font du lundi au vendredi inclus (sauf jours fériés) dans la mesure des
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