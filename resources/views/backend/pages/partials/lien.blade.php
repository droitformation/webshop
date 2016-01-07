<form data-validate-parsley class="form-horizontal">

    <div class="form-group">
        <label for="message" class="col-sm-2 control-label">Titre</label>
        <div class="col-sm-7">
            {!! Form::text('title', null , array('class' => 'form-control') ) !!}
        </div>
    </div>

    <div class="form-group">
        <label for="contenu" class="col-sm-2 control-label">Contenu</label>
        <div class="col-sm-7">
            {!! Form::textarea('content', null, array('class' => 'form-control  redactorSimple' )) !!}
        </div>
    </div>

</form>