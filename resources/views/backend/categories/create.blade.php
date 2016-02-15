@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{!!  url('admin/categories/'.$site)!!}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <!-- form start -->
            <form action="{!!  url('admin/categorie')!!}" enctype="multipart/form-data" method="POST" class="validate-form form-horizontal" data-validate="parsley">
                {!! csrf_field() !!}

            <div class="panel-heading">
                <h4>Ajouter une catégorie</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Titre</label>
                    <div class="col-sm-3">
                        {!! Form::text('title', null , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Site</label>
                    <div class="col-sm-3">
                        @if(!$sites->isEmpty())
                            <select class="form-control" name="site_id">
                                <option value="">Appartient au site</option>
                                @foreach($sites as $select)
                                    <option {{ $select->id == $site ? 'selected' : '' }}  value="{{ $select->id }}">{{ $select->nom }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="file" class="col-sm-3 control-label">Image</label>
                    <div class="col-sm-7">
                        <div class="list-group">
                            <div class="list-group-item">
                                {!!  Form::file('file')!!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                <div class="col-sm-3">
                    <input type="hidden" name="hideOnSite" value="0">
                    <input type="hidden" name="ismain" value="0">
                    <input type="hidden" name="site_id" value="{{ $site }}">
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </div>
            </div>

           </form>

        </div>
    </div>

</div>
<!-- end row -->

@stop