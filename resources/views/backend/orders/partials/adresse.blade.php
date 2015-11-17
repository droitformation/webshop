<div class="col-md-6">
    <div class="form-group ">
        <label class="control-label">Titre</label>
        {!! Form::select('adresse[civilite_id]', [4 => '',1 => 'Monsieur',2 => 'Madame',3 => 'Me'] , null, [ 'data-parsley-required' => 'true' ,'class' => 'form-control form-required']) !!}
    </div>
    <div class="form-group">
        <label class="control-label">Entreprise</label>
        <input type="text" name="adresse[company]" class="form-control" value="" placeholder="Entreprise">
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="control-label">Prénom</label>
            <input type="text" name="adresse[first_name]" data-parsley-required class="form-control form-required" value="" placeholder="Prénom">
        </div>
        <div class="col-md-6">
            <label class="control-label">Nom</label>
            <input type="text" name="adresse[last_name]" data-parsley-required class="form-control form-required" value="" placeholder="Nom">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">Adresse</label>
        <input type="text" name="adresse[adresse]" data-parsley-required class="form-control form-required" value="" placeholder="Adresse">
    </div>

</div>
<div class="col-md-6">

    <div class="form-group row">
        <div class="col-md-8">
            <label class="control-label">Complément d'adresse</label>
            <input type="text" name="adresse[complement]" class="form-control" value="" placeholder="Complément d'adresse">
        </div>
        <div class="col-md-4">
            <label class="control-label">Case Postale</label>
            <input type="text" name="" class="form-control" value="" placeholder="Case Postale">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label class="control-label">NPA</label>
            <input type="text" name="adresse[npa]" data-parsley-required class="form-control form-required" value="" placeholder="Code postal">
        </div>
        <div class="col-md-8">
            <label class="control-label">Localité</label>
            <input type="text" name="adresse[ville]" data-parsley-required class="form-control form-required" value="" placeholder="Localité">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-6">
            <label class="control-label">Canton</label>
            <?php $cantons = $cantons->lists('title','id')->all(); ?>
            {!! Form::select('adresse[canton_id]', [0 => 'Choix'] + $cantons , null, ['data-parsley-required' => 'true' ,'class' => 'form-control form-required']) !!}
        </div>
        <div class="col-md-6">
            <label class="control-label">Pays</label>
            {!! Form::select('adresse[pays_id]', $pays->lists('title','id')->all() , 208, [ 'data-parsley-required' => 'true' ,'class' => 'form-control form-required']) !!}
        </div>
    </div>

</div>