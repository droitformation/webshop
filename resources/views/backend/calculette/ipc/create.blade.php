@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/calculette/ipc')!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-6">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!!  url('admin/calculette/ipc')!!}" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

            <div class="panel-body event-info">
                <h4>Ajouter un IPC</h4>
                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Indice</label>
                    <div class="col-sm-9">
                        <input type="text" name="indice" value="" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Date</label>
                    <div class="col-sm-9">
                        <input type="text" name="start_at" value="" class="form-control datePicker" required>
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