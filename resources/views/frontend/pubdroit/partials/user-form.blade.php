<!-- Modal -->
<div class="modal fade" id="userFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <!-- form start -->
        {!! Form::open(array( 'method' => 'PUT','id' => 'pubdroit/updateAdresse', 'class' => 'form', 'url' => array('adresse/'.$user->adresse_livraison->id))) !!}
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modifier votre adresse de livraison</h4>
            </div>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="col-md-4 control-label">Entreprise</label>
                    <div class="col-md-8">
                        <input type="text" name="company" class="form-control" value="{{ $user->adresse_livraison->company }}" placeholder="Entreprise">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Titre</label>
                    <div class="col-md-8">
                        <?php $civilite = $user->adresse_livraison->civilite_id; ?>
                        <label class="radio-inline">
                            &nbsp;<input type="radio" data-parsley-required name="civilite" {{ $civilite == 1 ? 'checked' : ''}} value="1"> Monsieur&nbsp;
                        </label>
                        <label class="radio-inline">
                            &nbsp;<input type="radio" data-parsley-required name="civilite" {{ $civilite == 2 ? 'checked' : ''}} value="2"> Madame&nbsp;
                        </label>
                        <label class="radio-inline">
                            <input type="radio" data-parsley-required name="civilite" {{ $civilite == 3 ? 'checked' : ''}} value="3"> Me
                        </label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Prénom</label>
                    <div class="col-md-8">
                        <input type="text" name="first_name" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->first_name }}" placeholder="Prénom">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Nom</label>
                    <div class="col-md-8">
                        <input type="text" name="last_name" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->last_name }}" placeholder="Nom">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Adresse</label>
                    <div class="col-md-8">
                        <input type="text" name="adresse" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->adresse }}" placeholder="Adresse">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Complément d'adresse</label>
                    <div class="col-md-8">
                        <input type="text" name="complement" class="form-control" value="{{ $user->adresse_livraison->complement }}" placeholder="Complément d'adresse">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Case Postale</label>
                    <div class="col-md-3 col-xs-6">
                        <input type="text" name="cp" class="form-control" value="{{ $user->adresse_livraison->cp }}" placeholder="Case Postale">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Code postal</label>
                    <div class="col-md-3 col-xs-6">
                        <input type="text" name="npa" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->npa }}" placeholder="Code postal">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Localité</label>
                    <div class="col-md-8">
                        <input type="text" name="ville" data-parsley-required class="form-control form-required" value="{{ $user->adresse_livraison->ville }}" placeholder="Localité">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Canton</label>
                    <div class="col-md-8">
                        {!! Form::select('canton_id', $cantons->lists('title','id')->all() , $user->adresse_livraison->canton_id, ['data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Canton']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 control-label">Pays</label>
                    <div class="col-md-8">
                        {!! Form::select('pays_id', $pays->lists('title','id')->all() , $user->adresse_livraison->pays_id, [ 'data-parsley-required' => 'true' ,'class' => 'form-control form-required', 'placeholder' => 'Pays']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::hidden('id', $user->adresse_livraison->id) !!}
                {!! Form::hidden('user_id', $user->id) !!}
                {!! Form::hidden('livraison', 1) !!}
                {!! Form::hidden('profession_id', $user->adresse_livraison->profession_id) !!}
                {!! Form::hidden('type', $user->adresse_livraison->type) !!}
                <button type="button" class="btn btn-default" data-dismiss="modal">fermer</button>
                <button type="submit" id="updateSubmit" data-id="{{ $user->adresse_livraison->id }}" class="btn btn-primary">Sauver</button>
            </div>
        </div>
        </form>
    </div>
</div>
