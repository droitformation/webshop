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
                    <p style="{{$resetMargin}}margin-bottom:10px;{{ $resetPadding }}">Nous avons bien pris en compte votre inscription et vous remercions de votre intérêt.</p>
                </div>

                <!--infos colloque -->
                <table style="{{$resetMargin}}{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0; margin-bottom: 20px;" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="10px" style="background-color: #f6f6f6;">&nbsp;</td>
                        <td style="background-color: #f6f6f6;">
                            <h4 style="font-size: 15px; color:#000; margin-bottom: 5px;"><?php echo $colloque->titre; ?></h4>
                            <h5 style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px; color: #54565a;"><strong><?php echo $colloque->soustitre; ?></strong></h5><br>
                            <?php setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>

                            @if(isset($inscription) && !isset($inscription->groupe) && !$inscription->occurrences->isEmpty())

                                @foreach($inscription->occurrences as $occurrence)
                                    <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;"><strong>Titre:</strong> {{ $occurrence->title }}</p>
                                    <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;"><strong>Lieu:</strong> {{ $occurrence->location->name }}</p>
                                    <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px; margin-bottom: 10px;"><strong>Date:</strong> {{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}</p>
                                @endforeach

                            @else
                                @if(!$colloque->occurrences->isEmpty() && $colloque->occurrences->pluck('starting_at')->unique()->count() > 1)

                                    <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;"><strong>Dates:</strong></p>

                                    <ul style="margin: 5px 5px 5px 5px; padding-left:15px; list-style: disc;">
                                        @foreach($colloque->occurrences as $occurrence)
                                            <li style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;">{{ $occurrence->starting_at->formatLocalized('%d %B %Y') }}</li>
                                        @endforeach
                                    </ul>

                                @else
                                    <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;"><strong>Date:</strong> <?php echo $colloque->event_date; ?></p>
                                @endif

                                <p style="{{ $resetPadding }}{{ $resetMargin }} font-size: 14px; line-height: 20px;"><strong>Lieu:</strong> <?php echo $colloque->location->name.', '.strip_tags($colloque->location->adresse); ?></p>
                            @endif

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
                                <?php echo (in_array('bon',$annexes) ?     '<li>Les bons de participation à présenter lors de l\'arrivée des participants</li>' : ''); ?>
                                <?php echo (in_array('facture',$annexes) ? '<li>La facture relative aux participations</li>' : ''); ?>
                                <?php echo (in_array('bv',$annexes) ?      '<li>Le bulletin de versement qui vous permettra de régler le montant des inscriptions dans les meilleurs délais.</li>' : ''); ?>
                            @else
                                <?php echo (in_array('bon',$annexes) ?     '<li>Le bon de participation à présenter lors de votre arrivée</li>' : ''); ?>
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
                    <!-- Notice desistement -->
                    <p style="{{$resetMargin}}{{ $resetPadding }}">
                        @if($colloque->notice)
                            {!! $colloque->notice !!}
                        @else
                            {!! Registry::get('inscription.infos.desistement') !!}
                        @endif
                    </p>

                    <!-- Salutations -->
                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}">Nous restons à disposition pour tout renseignement et vous adressons nos meilleures salutations.</p>
                    <p style="{{$resetMargin}}{{ $resetPadding }}{{ $style['mb-15'] }}color:#000;"><strong>Le secrétariat de la Faculté de droit</strong></p>
                </div>

            </td>
        </tr>

        @include('emails.partials.footer')
    </table>
    <!-- end .eBody -->

@stop