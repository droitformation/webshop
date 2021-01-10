@extends('backend.layouts.master')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <h3>Confirmation</h3>
    </div>
</div>
<!-- start row -->
<div class="row">

    <div class="col-md-12">
        <div class="panel panel-midnightblue">
            <div class="panel-body">
                <h3>Confirmer la suppression du compte {{ $user->name }}</h3>

                <form action="{{ url('admin/user/'.$user->id) }}" method="POST" class="form-horizontal">
                    <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}

                    <div class="row">

                        @if(!$user->adresses->isEmpty())
                            <div class="col-md-2">
                                <h4>Adresses</h4>
                                @foreach($user->adresses as $adresse)
                                    <address>
                                        @if(!empty($adresse->invoice_name))
                                            @foreach($adresse->invoice_name as $line)
                                                {{ $line }}<br/>
                                            @endforeach
                                        @endif
                                        {!! $adresse->adresse !!}<br/>
                                        {!! !empty($adresse->complement) ? $adresse->complement.'<br/>' : '' !!}
                                        {!! !empty($adresse->cp) ? $adresse->cp.'<br/>' : '' !!}
                                        {{ $adresse->npa }} {{ $adresse->ville }}
                                    </address>
                                @endforeach
                            </div>
                        @endif

                        @if(!$user->orders->isEmpty())
                            <div class="col-md-2">
                                <h4>Commandes</h4>
                                @foreach($user->orders as $order)
                                    <p>{{ $order->order_no }}</p>
                                @endforeach
                            </div>
                        @endif

                        @if(!$user->inscriptions->isEmpty())
                            <div class="col-md-2">
                                <h4>Inscriptions</h4>
                                @foreach($user->inscriptions as $inscription)
                                    <p>{{ $inscription->inscription_no }}</p>
                                @endforeach
                            </div>
                        @endif

                        @if(!$user->abos->isEmpty())
                            <div class="col-md-2">
                                <h4>Abonnements</h4>
                                @foreach($user->abos as $abo)
                                    <p>{{ $abo->abo->title }}</p>
                                @endforeach
                            </div>
                        @endif

                        <?php $subscriptions = !$user->email_subscriptions->isEmpty() ? $user->email_subscriptions->pluck('subscriptions')->flatten(1) : collect([]); ?>
                        @if(!$subscriptions->isEmpty())
                            <div class="col-md-2">
                                <h4>Newsletter</h4>
                                @foreach($subscriptions as $subscription)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="newsletter_id[]" checked  value="{{ $subscription->id }}">
                                            &nbsp; {{ $subscription->titre }}
                                        </label>
                                    </div>
                                @endforeach
                                <hr/>
                                <p class="text-muted">Cocher les abonnements à supprimer pour l'adresse email: {{ $user->email }}</p>
                                <p class="text-muted">Le reste sera gardé comme simple abonnement newsletter</p>
                            </div>
                        @endif

                    </div>

                    <hr/>

                    <input type="hidden" name="term" value="{{ session()->get('term') }}">
                    <input type="hidden" name="url" value="{{ url('admin/users') }}">
                    <input type="hidden" name="confirm" value="true">
                    <p class="text-right"><button class="btn btn-danger deleteAction" id="confirmUserDelete">Oui Supprimer</button></p>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- end row -->

@stop