@extends('emails.layouts.notification')
@section('content')

    <?php
    $resetMargin   = 'margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 0;';
    $resetPadding  = 'padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;';
    ?>

    <a style="{{ $fontFamily }} display:block; height: 115px;" href="{{ url('pubdroit') }}" target="_blank">
        <img width="max-width:100%;" src="{{ secure_asset('images/pubdroit/header_email.png') }}" alt="{{ config('app.name') }}">
    </a>

    <table style="{{ $style['email-body_inner_full'] }}" align="center" width="600" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }} padding: 25px 25px 20px 25px;">
                <div style="{{ $style['body_content'] }}">
                    <h2 style="{{$resetMargin}}margin-bottom:5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 16px;line-height: 28px;font-weight: bold;color: #000000;">Bonjour {{ $user->name }}</h2>
                    <p style="{{$resetMargin}}margin-bottom:10px;{{ $resetPadding }}color:#9b1a1a;">Après vérification de notre comptabilité, nous nous apercevons que la facture concernant le colloque susmentionné est due.</p>
                </div>

                <!--infos colloque -->
                <table style="{{$resetMargin}}{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0; margin-bottom: 20px;" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="10px" style="background-color: #f6f6f6;">&nbsp;</td>
                        <td style="background-color: #f6f6f6;">
                            <h4 style="font-size: 15px; color:#000; margin-bottom: 5px;"><?php echo $colloque->titre; ?></h4>
                            <h5 style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px; color: #54565a;"><strong><?php echo $colloque->soustitre; ?></strong></h5><br>
                            <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;"><strong>Date:</strong> <?php echo $colloque->event_date; ?></p>
                            <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;"><strong>Lieu:</strong> <?php echo $colloque->location->name.', '.strip_tags($colloque->location->adresse); ?></p>
                        </td>
                        <td width="10px" style="background-color: #f6f6f6;">&nbsp;</td>
                    </tr>
                    <tr><td height="5" colspan="3" style="background-color: #f6f6f6;">&nbsp;</td></tr>
                </table>

                <div style="{{ $style['body_content'] }}">
                    <!--infos participants -->
                    @if(isset($participants))
                        <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}"><strong>Participants:</strong></p>
                        <ul style="{{$resetMargin}}margin-bottom: 10px;margin-left: 15px;{{ $resetPadding }}">
                            @foreach($participants as $no => $participant)
                                <li>{{ $participant }} : <strong>{{ $no }}</strong></li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Annexes si elles existent (bon, facture, bv) -->
                @if(!empty($annexes))

                    <div style="{{ $style['body_content'] }}">
                        <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}"><strong>Vous trouverez ci-joint :</strong></p>

                        <ul style="{{$resetMargin}}margin-bottom: 10px;margin-left: 15px;{{ $resetPadding }}">
                            @if(isset($participants) && count($participants) > 1)
                                <?php echo (in_array('facture',$annexes) ? '<li>La facture relative aux participations</li>' : ''); ?>
                                <?php echo (in_array('bv',$annexes) ?      '<li>Le bulletin de versement qui vous permettra de régler le montant des inscriptions dans les meilleurs délais.</li>' : ''); ?>
                            @else
                                <?php echo (in_array('facture',$annexes) ? '<li>La facture relative à votre participation</li>' : ''); ?>
                                <?php echo (in_array('bv',$annexes) ?      '<li>Le bulletin de versement qui vous permettra de régler le montant de votre inscription dans les meilleurs délais.</li>' : ''); ?>
                            @endif
                        </ul>

                        @if(in_array('facture',$annexes) || in_array('bv',$annexes))
                            <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}">
                                <strong>A toutes fins utiles, les coordonnées ci-après vous permettront le règlement de votre facture via Internet.</strong>
                            </p>
                            <ul style="{{$resetMargin}}margin-bottom: 10px;margin-left: 15px;{{ $resetPadding }}">
                                <li>IBAN: {{ Registry::get('inscription.infos.iban') }}</li>
                                <li>BIC: {{ Registry::get('inscription.infos.bic') }}</li>
                            </ul>
                        @endif
                    </div>
                @endif

                <div style="{{ $style['body_content'] }}">
                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}color:#9b1a1a;">Merci de bien vouloir prendre connaissance des documents joints et de faire le versement au plus vite. </p>
                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}">Nous restons à disposition pour tout renseignement et vous adressons nos meilleures salutations.</p>
                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}color:#000;"><strong>Le secrétariat de la Faculté de droit</strong></p>
                </div>

            </td>
        </tr>

        @include('emails.partials.footer')
    </table>
    <!-- end .eBody -->

@stop