<!-- Inscription simple -->
<div class="clearfix"></div><hr>
<form role="form" class="validate-form" method="POST" action="{{ url('admin/inscription') }}" data-validate="parsley" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <fieldset>

        <div class="invoice_for">
            <address>
                <h4><strong>Facturé à:</strong></h4>
                {{ $user->name }}<br/>
                {{ $user->adresse_facturation->adresse }}<br/>
                {{ $user->adresse_facturation->npa }} {{ $user->adresse_facturation->ville }}
            </address>
            @include('backend.inscriptions.partials.references')
            <div class="invoice_rabais">

                <h4><strong>Choix du rabais</strong></h4>
                <div class="form-group">
                    <!-- Only public prices -->
                    <select name="rabais_id" class="form-control">
                        <option value="">Choix</option>
                        @foreach($rabais as $rabai)
                            <option value="{{ $rabai->id }}">{{ $rabai->title }} | {{ $rabai->value }} CHF</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if(!$colloque->prices->isEmpty())
            @include('backend.inscriptions.partials.prices', ['select' => 'price_id'])
        @endif

        <!-- Occurence if any -->
        @if(!$colloque->occurrences->isEmpty())
            <h4>Merci de préciser</h4>
            @include('backend.inscriptions..partials.occurrences', ['select' => 'occurrences[0]'])
        @endif

        @if(!$colloque->options->isEmpty())
            <h4>Merci de préciser</h4>
            @include('backend.inscriptions.partials.options', ['select' => 'groupes'])
        @endif

        <input name="user_id" value="{{ $user->id }}" type="hidden">
        <input name="colloque_id" value="{{ $colloque->id }}" type="hidden">
        <input name="type" value="{{ $type }}" type="hidden">

        <div class="form-group">
            <br><button id="makeInscription" class="btn btn-danger pull-right" type="submit">Inscrire</button>
        </div>
    </fieldset>
</form>
<!-- END Inscriptions -->