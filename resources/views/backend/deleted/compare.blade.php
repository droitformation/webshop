@extends('backend.layouts.master')
@section('content')


<div class="row">
    <div class="col-md-6">
        <h3>Adresses comparer</h3>
    </div>
    <div class="col-md-6 text-right"></div>
</div>

<div class="row">
    <div class="col-md-12">

        <div class='examples'>
            <div class='parent'>
                <div class='wrapper'>
                    <div id='middle-defaults' class='wide'>

                        @if(!$adresses->isEmpty())
                            @foreach($adresses as $adresse)
                                <div class="panel panel-default panel_33">
                                    <div class="panel-body panel-compare">

                                        <h1>{{ $adresse->name }}</h1>
                                        <h2>{{ $adresse->email }}</h2>
                                        <p>{{ $adresse->adresse }}</p>
                                        {!! !empty($adresse->complement) ? $adresse->complement.'<br>' : '' !!}
                                        {!! !empty($adresse->cp) ? $adresse->cp_trim.'<br>' : '' !!}
                                        {{ $adresse->npa }} {{ $adresse->ville }}<br>
                                        {{ isset($adresse->pays) ? $adresse->pays->title : '' }}

                                        <?php $person = isset($adresse->user) ? $adresse->user : $adresse; ?>
                                        <dl>
                                            @if(!$person->orders->isEmpty())
                                                <dt>Commandes</dt>
                                                @foreach($person->orders as $order)
                                                    <dd>{{ $order->order_no }}</dd>
                                                @endforeach
                                            @endif

                                            @if(isset($adresse->user) && !$adresse->user->inscriptions->isEmpty())
                                                <dt>Inscriptions</dt>
                                                @foreach($adresse->user->inscriptions as $inscription)
                                                    <dd>{{ $inscription->inscription_no }}</dd>
                                                @endforeach
                                            @endif
                                        </dl>

                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
                <div class='wrapper'>
                    <div id='left-defaults' class='container_dd'>

                    </div>
                    <div id='right-defaults' class='container_dd'>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-6">

    </div>
    <div class="col-md-6">


    </div>
</div>

@stop