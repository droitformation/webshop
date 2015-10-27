<div class="form-group">
    <label class="col-sm-3 control-label">Générer le <strong>bon de participation</strong></label>
    <div class="col-sm-5">
        <label class="radio-inline"><input type="radio" checked name="bon" value="1"> Oui</label>
        <label class="radio-inline"><input type="radio" name="bon" value="0"> Non</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Générer la <strong>facture</strong> et le <strong>BV</strong></label>
    <div class="col-sm-5">
        <label class="radio-inline"><input type="radio" checked name="facture" value="1"> Oui</label>
        <label class="radio-inline"><input type="radio" name="facture" value="0"> Non</label>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label">Compte pour BV</label>
    <div class="col-sm-5">
        <select name="compte_id" class="form-control">
            @if(!$comptes->isEmpty())
                @foreach($comptes as $compte)
                    <option value="{{ $compte->id }}">
                        {!! $compte->motif !!} |
                        {!! $compte->compte !!}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>