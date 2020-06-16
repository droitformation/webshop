
<div id="colloque-dependence">
    <div id="appVue">
        <form id="inscriptionForm" method="POST" action="{{ url('pubdroit/registration') }}">{!! csrf_field() !!}

            <?php $user = $user->load('primary_adresse'); ?>
            <?php $adresse_livraison   = $user->primary_adresse ? $user->primary_adresse : null; ?>
            <?php $adresse_facturation = $user->adresse_facturation ? $user->adresse_facturation : null; ?>

            <form-wizard @on-complete="onComplete" title="" subtitle=""
                         back-button-text="Précédent"
                         color="#b01d22"
                         next-button-text="Suivant">
                <tab-content title="Prix" :before-change="beforeTabSwitchPrices" backButtonText="Précédent">
                    @include('frontend.pubdroit.colloque.wizard.price')
                </tab-content>

                <tab-content title="Options" :before-change="beforeTabSwitch">
                    @include('frontend.pubdroit.colloque.wizard.option',['colloque' => $colloque])

                    <div id="colloque_options_wrapper"></div>
                </tab-content>

                @if(!$colloque->occurrences->isEmpty())
                    <tab-content title="Atelier/Lieux" :before-change="beforeTabSwitch">
                        @include('frontend.pubdroit.colloque.wizard.occurrence')
                    </tab-content>
                @endif

                <tab-content title="Adresse" :after-change="beforeTabSwitch">
                    <h4>Vérifier l'adresse</h4>

                    <div class="adresse-verify">
                        <address id="userAdresse">
                            <adresse-update
                                    :main="{{ $adresse_livraison }}"
                                    :original="{{ $adresse_livraison }}"
                                    title="Adresse de contact"
                                    type="1"></adresse-update>
                        </address>
                        <address id="userFacturation">
                            <adresse-update  :main="{{ $adresse_livraison }}"
                                             :original="{{ $adresse_facturation }}"
                                             title="Adresse de facturation <br><small>(si différente)</small>"
                                             type="4"></adresse-update>
                        </address>
                        <div class="references">
                            <div class="form-group">
                                <label class="control-label label-info-tooltip" for="reference_no">
                                    <strong>Votre N° de référence</strong>
                                    <button aria-label="Vous avez la possibilité d'indiquer un numéro de référence demandé par votre comptabilité." data-microtip-position="top-left" role="tooltip">  <i class="fa fa-info-circle"></i></button>
                                </label>
                                <input class="form-control" name="reference_no" id="reference_no" type="text" placeholder="Optionnel">
                            </div>
                            <div class="form-group">
                                <label class="control-label label-info-tooltip" for="transaction_no">
                                    <strong>Votre N° de commande</strong>
                                    <button aria-label="En plus du N° de référence vous avez la possibilité d'indiquer un numéro de commande." data-microtip-position="top-left" role="tooltip">  <i class="fa fa-info-circle"></i></button>
                                </label>
                                <input class="form-control" name="transaction_no" id="transaction_no" type="text" placeholder="Optionnel">
                            </div>
                        </div>
                    </div>

                </tab-content>
                <tab-content title="Confirmer" :after-change="lastTabResume">

                    <h4>Résumé</h4>
                    <p>Veuillez vérifier vos choix:</p>

                    <div class="adresse-verify">
                        <address id="userAdresse">
                            <adresse-update
                                    :main="{{ $adresse_livraison }}"
                                    :original="{{ $adresse_livraison }}"
                                    title="Adresse de livraison"
                                    type="1"></adresse-update>
                        </address>
                        <address id="userFacturation">
                            <adresse-update
                                    :main="{{ $adresse_livraison }}"
                                     :original="{{ $adresse_facturation }}"
                                     title="Adresse de facturation <br><small>(si différente)</small>"
                                     type="4"></adresse-update>
                        </address>
                    </div>

                    <div id="resumeWrapper"></div>

                    <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                    <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

                </tab-content>
                <template type="primary" slot="finish"><button class="btn wizard-btn primary">Envoyer</button></template>
            </form-wizard>
            <div id="errordiv"></div>
        </form>
    </div>
</div>
