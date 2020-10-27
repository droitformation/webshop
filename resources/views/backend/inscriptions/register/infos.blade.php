<div class="invoice_for">
    <address>
        <h4><strong>Facturé à:</strong></h4>
        {{ $user->name }}<br/>
        {{ $user->adresse_facturation->adresse }}<br/>
        {{ $user->adresse_facturation->npa }} {{ $user->adresse_facturation->ville }}
    </address>
    <div class="invoice_reference">
        <h4><strong>Références:</strong></h4>
        <div class="input-group">
            <span class="input-group-addon">N° référence</span>
            <input type="text" class="form-control" value="{{ old('reference_no') }}" name="reference_no">
        </div><br>

        <div class="input-group">
            <span class="input-group-addon">N° commande</span>
            <input type="text" class="form-control" value="{{ old('transaction_no') }}" name="transaction_no">
        </div>
    </div>
    @if(isset($rabais) && !$rabais->isEmpty())
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
    @endif
</div>