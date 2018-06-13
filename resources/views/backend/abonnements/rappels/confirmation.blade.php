@extends('backend.layouts.master')
@section('content')

    <p><a href="{{ url('admin/rappel/'.$product->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> &nbsp;Retour aux rappels</a></p>

    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">
                <div class="panel-body">

                    <h3 class="text-info">Confirmer et envoyer les rappels</h3>

                    @if(!$factures->isEmpty())
                        <form action="{{ url('admin/rappel/send') }}" method="POST">{!! csrf_field() !!}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="row">
                                <div class="col-md-6"><input id="select_all" type="checkbox"> &nbsp;Tout cocher/décocher</div>
                                <div class="col-md-6 text-right"><button class="btn btn-info" type="submit"><i class="fa fa-bell"></i> &nbsp;Envoyer les rappels</button></div>
                            </div>

                            <div class="checkbox_all">
                                @foreach($factures as $facture)
                                    @if(isset($facture->abonnement))
                                        <?php $user = $facture->abonnement->user_facturation; ?>

                                        <div class="input-wrapper">
                                            <input type="checkbox" class="rappel-input" {{ $facture->abonnement->substitute_email ? 'disabled' : 'checked' }} name="factures[]" value="{{ $facture->id }}" />
                                            {!! $user ? $user->name : '<p><span class="label label-warning">Duplicata</span></p>' !!}

                                            @if($facture->abonnement->substitute_email)
                                                <div class="text-danger" style="margin: 3px 0;">
                                                    <p>L'email du compte est un email de substitution.<br/>
                                                        @if(!empty($user->username))
                                                            Le rappel sera envoyé à l'email global : {{ $user->username }}
                                                        @else
                                                            <?php $id = isset($user->user) ? $user->user_id : $user->id; ?>
                                                            <a href="{{ url('admin/user/'.$id) }}">Ajouter un email global</a>
                                                        @endif
                                                    </p>
                                                </div>
                                            @endif

                                            <a target="_blank" href="{{ url('preview/aborappel/'.$facture->id) }}" class="btn btn-default btn-sm pull-right">Voir l'email envoyé</a>

                                            <p><strong>{{ $facture->abonnement->abo->title }} | {{ $facture->prod_edition}}</strong></p>
                                        </div>

                                    @endif
                                @endforeach
                            </div><br/>
                            <p class="text-right"><button class="btn btn-info pull-right" type="submit"><i class="fa fa-bell"></i> &nbsp;Envoyer les rappels</button></p>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

@stop