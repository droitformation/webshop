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
        <td class="eBody" style="{{ $resetMargin }}padding-top: 12px;padding-bottom: 8px;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;width: 512px;color: #54565c;border-left: 1px solid #b3bdca;border-right: 1px solid #b3bdca;">
            <h2 style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 16px;line-height: 28px;font-weight: bold;color: #000000;">
                Bonjour {{ $inscription->user->name }}
            </h2>
            <p style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}">
                Nous avons bien pris en compte votre inscription et vous remercions de votre intérêt.
            </p>
            <p style="{{$resetMargin}}margin-bottom: 5px;{{ $resetPadding }}">
                Vous trouverez ci-joint :

                Le bon de participation à présenter lors de votre arrivée
                La facture relative à votre participation
                Le bulletin de versement qui vous permettra de régler le montant de votre inscription dans les meilleurs délais.

                A toutes fins utiles, les coordonnées ci-après vous permettront le règlement de votre facture via Internet.
                IBAN :CH11 0900 0000 2000 4130 2
                BIC :POFICHBEXXX

                Les désistements sont acceptés sans frais jusqu'à 15 jours avant le séminaire. Passé ce délai, le montant de l'inscription n'est plus remboursé. Il est toutefois possible de vous faire remplacer.
                Nous restons à disposition pour tout renseignement et vous adressons nos meilleures salutations.
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