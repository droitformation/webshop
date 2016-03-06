<!-- Autocomplete for adresse -->
<div class="autocomplete-wrapper">
    <div class="input-adresse" data-adresse="{{ $adresse_id }}" data-type="{{ $type }}">
        <input type="hidden" class="form-control" value="{{ $adresse_id }}" name="{{ $type }}">
    </div>
    <div class="choice-adresse"></div>
    <div class="collapse {{ !$adresse_id ? 'in' : '' }} adresse-find">
        <div class="form-group">
            <input id="search-adresse{{ $type }}" class="form-control search-adresse" placeholder="Chercher une adresse..." type="text">
        </div>
    </div>
</div>
<!-- End Autocomplete for adresse -->