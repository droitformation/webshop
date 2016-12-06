@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/attribut')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-8">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!!  url('admin/attribut') !!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

            <div class="panel-body event-info">
                <h4>Ajouter un attribut</h4>
                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Titre</label>
                    <div class="col-sm-9">
                        <input type="text" name="title" value="" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><strong>Utilisé comme rappel</strong></label>
                    <div class="col-sm-5">
                        <label class="radio-inline"><input type="radio" name="reminder" value="1"> Oui</label>
                        <label class="radio-inline"><input type="radio" name="reminder" value="0" checked> Non</label>
                    </div>
                    <div class="col-sm-4">
                        <p class="text-muted">Visible que dans l'admin</p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Interval</label>
                    <div class="col-sm-7">
                        <select class="form-control" name="intervals">
                            <option value="">Choix</option>
                            <option value="week">1 semaine</option>
                            <option value="month">1 mois</option>
                            <option value="trimester">3 mois</option>
                            <option value="semester">6 mois</option>
                            <option value="year">1 an</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Text pour le rappel</label>
                    <div class="col-sm-9">
                        <textarea name="text" cols="50" rows="4" class="redactorSimple form-control"></textarea>
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </div>
            </div>

           </form>

        </div>
    </div>

</div>
<!-- end row -->

@stop