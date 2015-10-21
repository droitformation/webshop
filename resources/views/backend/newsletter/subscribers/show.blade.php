@extends('backend.layouts.master')
@section('content')

<div class="row">
    <div class="col-md-offset-2 col-md-8">

        <div class="options" style="margin-bottom: 10px;">
            <div class="btn-toolbar">
                <a href="{{ url('admin/subscriber') }}" class="btn btn-inverse"><i class="fa fa-chevron-left"></i> &nbsp;Retour aux abonnés</a>
            </div>
        </div>

        <div class="panel panel-sky">

            <form action="{{ url('admin/subscriber/'.$subscriber->id) }}" enctype="multipart/form-data" data-validate="parsley" method="POST" class="validate-form form-horizontal">
                <input type="hidden" name="_method" value="PUT">
                {!! csrf_field() !!}

                <div class="panel-heading">
                    <h4>&Eacute;diter un abonné</h4>
                </div>
                <div class="panel-body event-info">

                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-6">
                            {!! Form::text('email', $subscriber->email , array('class' => 'form-control') ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Status</label>
                        <div class="col-sm-6">
                           <?php $status = ($subscriber->activated_at ? 1 : 0); ?>
                           {!! Form::select('activation', array('1' => 'Confirmé', '0' => 'Non confirmé'), $status , array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Abonnements</label>
                        <div class="col-sm-6">

                            <?php $abos = (!$subscriber->subscriptions->isEmpty() ? $subscriber->subscriptions->lists('id')->all() : []); ?>

                            @if(!$newsletter->isEmpty())
                                @foreach($newsletter as $abonnement)
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="newsletter_id[]" <?php if(in_array($abonnement->id,$abos)){ echo 'checked'; } ?> value="{{ $abonnement->id }}">
                                        {{ $abonnement->titre }}
                                    </label>
                                </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
                <div class="panel-footer mini-footer ">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        {!! Form::hidden('id', $subscriber->id) !!}
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>

@stop
