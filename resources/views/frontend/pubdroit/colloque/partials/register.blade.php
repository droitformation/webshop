
<div id="colloque-dependence">

    <div id="appVue">
        <form id="inscriptionForm" method="POST" action="{{ url('pubdroit/registration') }}">{!! csrf_field() !!}
            <form-wizard @on-complete="onComplete" title="Inscription" subtitle="">
                <tab-content title="Prix" :before-change="beforeTabSwitch">
                    @include('frontend.pubdroit.colloque.wizard.price')
                </tab-content>
                <tab-content title="Options" :before-change="beforeTabSwitch">
                    @include('frontend.pubdroit.colloque.wizard.option')
                </tab-content>
                @if(!$colloque->occurrences->isEmpty())
                    <tab-content title="Ateliers" :before-change="beforeTabSwitch">
                        @include('frontend.pubdroit.colloque.wizard.occurrence')
                    </tab-content>
                @endif
                <tab-content title="Adresse" :after-change="beforeTabSwitch">
                    <h4>Vérifier l'adresse</h4>

                    <?php $adresse_livraison   = $user->adresse_livraison ? $user->adresse_livraison : null; ?>
                    <?php $adresse_facturation = $user->adresse_facturation ? $user->adresse_facturation : null; ?>

                    <facturation-adresse
                            :main="{{ $adresse_livraison->id }}"
                            :livraison="{{ json_encode($adresse_livraison) }}"
                            :facturation="{{ json_encode($adresse_facturation) }}">
                    </facturation-adresse>
                </tab-content>
                <tab-content title="Confirmer" :after-change="lastTabResume">

                    <h4>Fin</h4>
                    <p>You have successfully completed all steps.</p>


                    <input name="user_id" value="{{ Auth::user()->id }}" type="hidden">
                    <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">

                </tab-content>
            </form-wizard>
            <div id="errordiv"></div>
        </form>
    </div>
</div>
