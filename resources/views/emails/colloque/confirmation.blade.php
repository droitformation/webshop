@extends('emails.layouts.shop')
@section('content')

    <?php

        $resetMargin   = 'margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 0;';
        $resetPadding  = 'padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;';
        $emptyCell     = '<td class="emptyCell" style="'.$resetMargin.''.$resetPadding.'border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #f6f6f7;line-height: 0 !important;font-size: 0 !important;">&nbsp;</td>';
        $tdTableTitle  = '-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 40px;padding-top: 6px;padding-bottom: 6px;padding-left: 12px;padding-right: 12px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #505050;background-color: #ebebeb;border-bottom: 1px solid #e3e3e3;border-top: 1px solid #e3e3e3;';
        $desktopHide   = 'display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;';
        $tdTableRow    = 'padding-top: 5px;padding-bottom: 5px;padding-left: 12px;padding-right: 12px;height:30px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: middle;font-size: 14px;line-height: 16px;color: #54565c;background-color: #ffffff;border-bottom: 1px solid #e3e3e3;';
    ?>
    <tr>
        <td class="eBody" style="{{ $resetMargin }}padding-top: 12px;padding-bottom: 8px;padding-left: 20px;padding-right: 20px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;width: 512px;color: #54565c;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;">

            <h2 style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 16px;line-height: 28px;font-weight: bold;color: #000000;">
                Bonjour {{ $inscription->user->name }}
            </h2>
            <p style="{{$resetMargin}}{{ $resetPadding }}">
                Nous avons bien pris en compte votre inscription et vous remercions de votre intérêt.
            </p>


            <table style="{{$resetMargin}}{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td colspan="3" style="{{ $resetPadding }}{{ $resetMargin }}background-color: #fff; height: 10px;line-height: 0;">&nbsp;</td></tr>
                <tr><td colspan="3" style="{{ $resetPadding }}{{ $resetMargin }}background-color: #f2f2f2;height: 8px;line-height: 0;">&nbsp;</td></tr>
                <tr>
                    <td width="10px" style="background-color: #f2f2f2;">&nbsp;</td>
                    <td style="background-color: #f2f2f2;">
                        <h4 style="font-size: 16px; color:#1a446e;"><?php echo $inscription->colloque->titre; ?></h4>
                        <p style="{{ $resetPadding }}{{ $resetMargin }}margin-bottom:5px; color: #2d2d2d;"><strong><?php echo $inscription->colloque->soustitre; ?></strong> </p>
                        <p style="{{ $resetPadding }}{{ $resetMargin }}color: #2d2d2d;"><strong>Date:</strong> <?php echo $inscription->colloque->event_date; ?></p>
                        <p style="{{ $resetPadding }}{{ $resetMargin }}color: #2d2d2d;"><strong>Lieu:</strong> <?php echo $inscription->colloque->location->name.', '.$inscription->colloque->location->adresse; ?></p>
                    </td>
                    <td width="10px" style="background-color: #f2f2f2;">&nbsp;</td>
                </tr>
                <tr><td colspan="3" style="{{ $resetPadding }}{{ $resetMargin }}background-color: #f2f2f2; height: 8px;line-height: 0;">&nbsp;</td></tr>
                <tr><td colspan="3" style="{{ $resetPadding }}{{ $resetMargin }}background-color: #fff; height: 10px;line-height: 0;">&nbsp;</td></tr>
            </table>

            <!-- Annexes si elles existent (bon, facture, bv) -->
            <?php if(!empty($annexes)){ ?>

                <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}">
                    <strong>Vous trouverez ci-joint :</strong>
                </p>

                <ul style="{{$resetMargin}}margin-bottom: 10px;margin-left: 15px;{{ $resetPadding }}">
                    <?php echo (in_array('bon',$annexes) ? '<li>Le bon de participation à présenter lors de votre arrivée</li>' : ''); ?>
                    <?php echo (in_array('facture',$annexes) ? '<li>La facture relative à votre participation</li>' : ''); ?>
                    <?php echo (in_array('bv',$annexes) ? '<li>Le bulletin de versement qui vous permettra de régler le montant de votre inscription dans les meilleurs délais.</li>' : ''); ?>
                </ul>

                <?php if(in_array('facture',$annexes) || in_array('bv',$annexes)){ ?>
                    <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}">
                        <strong>A toutes fins utiles, les coordonnées ci-après vous permettront le règlement de votre facture via Internet.</strong>
                    </p>

                    <ul style="{{$resetMargin}}margin-bottom: 10px;margin-left: 15px;{{ $resetPadding }}">
                        <li>IBAN :{{ config('documents.emails.iban') }}</li>
                        <li>BIC :{{ config('documents.emails.bic') }}</li>
                    </ul>
                <?php  }  ?>

            <?php  }  ?>

            <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}">
                {!! config('documents.emails.desistement') !!}
            </p>
            <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}">
                Nous restons à disposition pour tout renseignement et vous adressons nos meilleures salutations.
            </p>
            <p style="{{$resetMargin}}margin-bottom: 10px;{{ $resetPadding }}">
                Le secrétariat de la Faculté de droit
            </p>

        </td>
        <!-- end .eBody-->
    </tr>
    <tr>
        <td style="{{$resetMargin}}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;" height="10px"></td>
    </tr>

    <!-- end .eBody -->

@stop