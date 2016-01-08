<form data-validate-parsley class="form-horizontal">

    <h4>Bloc lien</h4>

    <div class="form-group">
        <label for="message" class="col-sm-2 control-label">Titre</label>
        <div class="col-sm-9">
            {!! Form::text('title', null , ['class' => 'form-control'] ) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="message" class="col-sm-2 control-label">Lien</label>
        <div class="col-sm-9">
            {!! Form::text('lien', null , ['class' => 'form-control'] ) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="contenu" class="col-sm-2 control-label">Contenu</label>
        <div class="col-sm-9">
            {!! Form::textarea('content', null, ['class' => 'form-control redactorBlocSimple'] ) !!}
        </div>
    </div>

</form>