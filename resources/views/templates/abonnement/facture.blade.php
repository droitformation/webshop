@extends('templates.layouts.master')
@section('content')

<?php $abo     = $generate->getAbo(); ?>
<?php $facture = $generate->getFacture(); ?>

<div id="content">
    <div id="header-main">
        <table id="content-table">
            <tr><td colspan="2" height="30">&nbsp;</td></tr>
            <tr>
                <td><img height="70mm" id="logoImg" src="{{ public_path('files/main/'.\Registry::get('abo.infos.logo')) }}" alt="Unine logo" /></td>
                <td align="right">
                    @if($abo->abo->logo_file)
                        <img height="65mm" src="{{ public_path('files/main/'.$abo->abo->logo_file) }}" alt="logo" />
                    @endif
                </td>
            </tr>
            <tr><td colspan="2" height="10">&nbsp;</td></tr>
            <tr align="top">
                <td align="top" width="60%" valign="top">
                    <div id="facdroit">
                        {!! ($abo->abo->name ? '<li>'.$abo->abo->name.'</li>' : '') !!}
                        <li>{!! \Registry::get('abo.infos.nom') !!}</li>
                        <li class="mb-5">{!! \Registry::get('abo.infos.adresse') !!}</li>
                        {!!  !empty(\Registry::get('shop.infos.telephone')) ? '<li>Tél. '.\Registry::get('shop.infos.telephone').'</li>' : '' !!}
                        @if($abo->abo->id == 2)
                            <li>seminaire.bail@unine.ch</li>
                        @else
                            {!! !empty(\Registry::get('shop.infos.email')) ? '<li>'.\Registry::get('shop.infos.email').'</li>' : '' !!}
                        @endif
                    </div>
                </td>
                <td align="top" width="40%" valign="top">
                    @include('templates.partials.adresse',['adresse' => $generate->getAdresse()])
                </td>
            </tr>
        </table>
    </div>

    <h1 class="title blue">
        <?php $rappel = (isset($rappel) ? '<span class="red">'.$rappel.''.($rappel > 1 ? 'ème' : 'ère').' Rappel</span>' : ''); ?>
        {!! $rappel !!} Facture
    </h1>

    <table class="content-table">
        <tr>
            <td width="33%" align="middle" class="misc-infos">
                <h4><strong>{{ $facture->abo_ref }}</strong></h4>
            </td>
            <td width="1%"  align="top"></td>
            <td width="30%" align="left" style="text-align: left;" class="misc-infos">
                <?php if(!empty($tva)) { echo \Registry::get('shop.infos.tva'); } ?>
                <div class="coordonnees">
                    <h4>Coordonnées pour le paiement</h4>
                    <p>IBAN: {{ \Registry::get('inscription.infos.iban') }}</p>
                </div>
            </td>
            <td width="1%" align="top" valign="top"></td>
            <td width="35%" align="top" valign="middle" class="misc-infos">
                <table id="content-table" class="infos">
                    <tr>
                        <td width="20%"><strong class="blue">Date:</strong></td>
                        <td width="80%">
                            {{ isset($rappel) && !empty($rappel) ? \Carbon\Carbon::now()->formatLocalized('%d %B %Y') :  $facture->created_at->formatLocalized('%d %B %Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td width="28%"><strong class="blue">Total:</strong></td>
                        <td width="72%">{{ $abo->price_cents }} CHF</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td colspan="2" height="1">&nbsp;</td></tr>
    </table>

    <!-- Total calculations -->
    <div id="abo-table">
        <?php $traduction = ['year' => 'annuelle', 'semester' => 'semestrielle', 'month' => 'mensuelle']; ?>
        <h2>Abonnement à la publication {{ $traduction[$abo->abo->plan] }} <strong>{{ $abo->abo->title }}</strong>
          @if($generate->isTiers())
            géré par vos soins :
          @endif
        </h2>

        <table class="content-table content-abo">
            @if($generate->isTiers())
                <tr>
                    <td colspan="3" style="padding-top: 4px;">
                        <p><cite>
                            Destinataire : {{ $generate->getDetenteur()->name }},
                                {{ $generate->getDetenteur()->company }} n/réf.
                                {{ isset($abo->reference) && !empty($abo->reference) ? $abo->reference : $abo->numero }}
                        </cite></p>
                    </td>
                </tr>
            @endif
            <tr><td colspan="2" height="1">&nbsp;</td></tr>
            <tr>
                <td width="70%">{{ $abo->exemplaires }} exemplaire{{ ($abo->exemplaires > 1) ? 's' : '' }} du numéro {{ $facture->prod_edition }}</td>
                <td width="30%"></td>
            </tr>
            @if($abo->exemplaires > 1)
                <tr><td colspan="2" height="1">&nbsp;</td></tr>
                <tr>
                    <td width="70%">à CHF {{ $abo->abo->current_product->price_cents }}/pce</td>
                    <td width="30%"></td>
                </tr>
            @endif

            <tr>
                <td width="90%" class="pad-b" align="right">{{ $abo->exemplaires }}x &nbsp;&nbsp;</td>
                <td width="10%" class="pad-b" align="right"><strong>{{ isset($abo->abo->current_product) ? $abo->abo->current_product->price_cents : '' }} CHF</strong></td>
            </tr>

            @if($generate->isTiers() && $abo->price_cents_remise)
                <tr>
                    <td class="pad-b" width="90%" align="right">Votre remise &nbsp;&nbsp;</td>
                    <td class="pad-b" width="10%" align="right"><strong>{{ $abo->price_cents_remise }} CHF</strong></td>
                </tr>
            @endif

            @if($abo->abo->shipping)
                <tr>
                    <td class="pad-b" width="90%" align="right">Frais de port &nbsp;&nbsp;</td>
                    <td class="pad-b" width="10%" align="right"><strong>{{ $abo->shipping_cents }} CHF</strong></td>
                </tr>
            @endif

            <tr>
                <td width="70%" align="right" style="padding-top: 4px;"><strong>Total</strong> ({{ \Registry::get('shop.infos.taux_reduit') }}% TVA incluse)</td>
                <td width="30%" align="right" style="border-top: 1px solid #000; padding-top: 4px;"><strong>{{ $abo->price_cents }} CHF</strong></td>
            </tr>

            <tr><td colspan="2" height="1">&nbsp;</td></tr>
            <tr>
                <td colspan="2" width="80%" align="left">
                    <small>Conditions de paiement {{ \Registry::get('abo.days') }} jours net</small>
                </td>
            </tr>
        </table>

    </div>

    <table id="content-table">
        <tr><td colspan="2" height="5">&nbsp;</td></tr>
        <tr>
            <!-- Messages for customer -->
            <td width="62%" align="top" valign="top">

                <h3>Communications</h3>
                <div class="communications"><?php echo \Registry::get('shop.abo.message'); ?></div>
                <div class="communications">

                    @if($abo->payed_at)
                        <p class="message special">Acquitté le {{ $abo->payed_at->format('d/m/Y') }}</p>
                    @endif

                    @if(!empty(\Registry::get('abo.message')))
                        {!! \Registry::get('abo.message') !!}
                    @endif

                    @if(!empty($messages))
                        @foreach($msgTypes as $msgType)
                            @if(isset($messages[$msgType]) && !empty($messages[$msgType]))
                                <p class="message '.$msgType.'">{{ $messages[$msgType] }}</p>
                            @endif
                        @endforeach
                        <br/>
                    @endif

                    <p class="message">{{ $messages['remerciements'] }}</p><br/>
                    <p class="message">
                        Neuchâtel, le
                        {{ isset($rappel) && !empty($rappel) ? \Carbon\Carbon::now()->formatLocalized('%d %B %Y') :  $facture->created_at->formatLocalized('%d %B %Y') }}
                    </p>
                </div>

            </td>
            <td width="5%" align="top" valign="top"></td>
        </tr>
    </table>
</div>

<!-- BV -->
<?php list($francs,$centimes) = $abo->price_total_explode; ?>

<table id="bv-table" style="background: none;">
    <tr align="top" valign="top">
        <td width="60mm" align="top" valign="top">
            <table id="recu" valign="top">
                <tr>
                    <td align="top" valign="center" height="43mm">
                        <ul class="versement">
                            @if(!empty($abo->abo->adresse))
                                <li>{!! $abo->abo->adresse !!}</li>
                            @elseif(!empty($versement))
                                @foreach($versement as $line)
                                    <li>{!! $line !!}</li>
                                @endforeach
                            @endif
                        </ul>
                    </td>
                </tr>
                <tr><td align="top" valign="center" height="7.6mm" class="compte">{{ !empty($abo->abo->compte) ? $abo->abo->compte : $compte }}</td></tr>
                <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs">{{ $francs }}</span>{{ $centimes }}</td></tr>
            </table>
        </td>
        <td width="62mm" align="top" valign="top">
            <table id="compte" valign="top">
                <tr>
                    <td align="top" valign="center" height="43mm">
                        <ul class="versement">
                            @if(!empty($abo->abo->adresse))
                                <li>{!! $abo->abo->adresse !!}</li>
                            @elseif(!empty($versement))
                                @foreach($versement as $line)
                                    <li>{!! $line !!}</li>
                                @endforeach
                            @endif
                        </ul>
                    </td>
                </tr>
                <tr><td align="top" valign="center" height="7.6mm" class="compte">{{ !empty($abo->abo->compte) ? $abo->abo->compte : $compte }}</td></tr>
                <tr><td align="top" valign="center" height="6mm" class="price"><span class="francs">{{ $francs }}</span>{{ $centimes }}</td></tr>
            </table>
        </td>
        <td width="88mm" align="top" valign="top">
            <table id="versement" valign="top">
                <tr>
                    <td align="top" valign="top" width="64%" height="20mm">
                        <ul class="versement">
                            <li>{{ $abo->abo->title }}</li>
                            <li>{{ $facture->abo_ref }}</li>
                        </ul>
                    </td>
                    <td align="top" valign="top" width="32%" height="20mm"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@stop