
<div id="colloque-dependence">
    <div id="appVue">
        <form id="inscriptionForm" method="POST" action="{{ url('pubdroit/registration') }}">{!! csrf_field() !!}

            <form-wizard @on-complete="onComplete" title="" subtitle=""
                         back-button-text="Précédent"
                         color="#b01d22"
                         next-button-text="Suivant">
                <tab-content title="Prix" :before-change="beforeTabSwitch" backButtonText="Précédent">
                    @include('frontend.pubdroit.colloque.wizard.price')
                </tab-content>
                <tab-content title="Options" :before-change="beforeTabSwitch">
                    @include('frontend.pubdroit.colloque.wizard.option')
                </tab-content>
                @if(!$colloque->occurrences->isEmpty())
                    <tab-content title="Atelier/Lieux" :before-change="beforeTabSwitch">
                        @include('frontend.pubdroit.colloque.wizard.occurrence')
                    </tab-content>
                @endif
                <tab-content title="Adresse" :after-change="beforeTabSwitch">
                    <h4>Vérifier l'adresse</h4>

                    <?php $adresse_livraison   = $user->adresse_livraison ? $user->adresse_livraison : null; ?>
                    <?php $adresse_facturation = $user->adresse_facturation ? $user->adresse_facturation : null; ?>

                    <div class="adresse-verify">
                        <address id="userAdresse">
                            <adresse-update :original="{{ $adresse_livraison }}" title="Adresse indiqué sur bon"></adresse-update>
                        </address>
                        <address id="userFacturation">
                            <adresse-update :original="{{ $adresse_facturation }}" title="Adresse indiqué sur facture"></adresse-update>
                        </address>
                        <div>
                            <div class="form-group">
                                <label class="control-label" for="reference_no">N° référence</label>
                                <input class="form-control" name="reference_no" id="reference_no" type="text" placeholder="Optionnel">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="transaction_no">N° commande</label>
                                <input class="form-control" name="transaction_no" id="transaction_no" type="text" placeholder="Optionnel">
                            </div>
                        </div>
                    </div>

                    <facturation-adresse
                            :main="{{ $adresse_livraison->id }}"
                            :livraison="{{ json_encode($adresse_livraison) }}"
                            :facturation="{{ json_encode($adresse_facturation) }}">
                    </facturation-adresse>
                </tab-content>
                <tab-content title="Confirmer" :after-change="lastTabResume">

                    <h4>Résumé</h4>
                    <p>Veuillez vérifier vos choix:</p>

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
