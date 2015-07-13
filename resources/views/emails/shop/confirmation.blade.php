@extends('emails.layouts.shop')
@section('content')

    <?php

        $resetMargin   = 'margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 0;';
        $resetPadding  = 'padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 0;';
        $emptyCell        = '<td class="emptyCell" style="'.$resetMargin.''.$resetPadding.'border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #f6f6f7;line-height: 0 !important;font-size: 0 !important;">&nbsp;</td>';

    ?>        
    <tr>
        <td class="eBody" style="{{ $resetMargin }}padding-top: 12px;padding-bottom: 0;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;width: 512px;color: #54565c;background-color: #ffffff;border-left: 4px solid #b3bdca;border-right: 4px solid #b3bdca;">
            <h1 style="margin-top: 0;margin-left: 0;margin-right: 0;margin-bottom: 5px;{{ $resetPadding }}-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;font-size: 24px;line-height: 36px;font-weight: bold;color: #465059;">
                Project Name
            </h1>

            Bonjour {{ $user->name }}
            Merci pour votre commande sur www.publications-droit.ch
            www.publications-droit.ch

        </td>
        <!-- end .eBody-->
    </tr>
    <tr>
        <td class="blank" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;border-left: 4px solid #b3bdca;border-right: 4px solid #b3bdca;"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="invoiceTable2" style="{{ $resetMargin }}{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;background-color: #ffffff;">
                <tr>
                    <th class="width312" style="-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: left;vertical-align: top;width: 312px;padding-top: 0;padding-bottom: 6px;padding-left: 16px;padding-right: 16px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #a1a2a5;background-color: #ffffff;border-bottom: 1px solid #ebebeb;">
                        Summary
                    </th>
                    <th class="width84 alignRight" style="-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;padding-top: 0;padding-bottom: 6px;padding-left: 16px;padding-right: 16px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #a1a2a5;background-color: #ffffff;border-bottom: 1px solid #ebebeb;">
                        Quantity
                    </th>
                    <th class="width84 alignRight" style="-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;padding-top: 0;padding-bottom: 6px;padding-left: 16px;padding-right: 16px;font-size: 12px;line-height: 16px;font-weight: bold;text-transform: uppercase;color: #a1a2a5;background-color: #ffffff;border-bottom: 1px solid #ebebeb;">
                        Subtotal
                    </th>
                </tr>
                <tr>
                    <td class="width312" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: left;vertical-align: top;width: 312px;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        Interaction Design <br>
                        <span class="servDetails" style="font-size: 12px;color: #a1a2a5;">
                            Design compositions (based on client wireframe)<br>
                            One round of changes
                        </span>
                    </td>
                    <td class="width84 alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">Quantity: </span>1
                    </td>
                    <td class="width84 alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">Subtotal: </span>
                        <span class="amount" style="color: #3cbff3;">$600</span>
                    </td>
                </tr>
                <tr>
                    <td class="width312" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: left;vertical-align: top;width: 312px;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        HTML/CSS
                    </td>
                    <td class="width84 alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">
                            Quantity:
                        </span>2
                    </td>
                    <td class="width84 alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">
                            Subtotal:
                        </span>
                        <span class="amount" style="color: #3cbff3;">
                            $400
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="subTotal alignRight mobileHide" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;font-size: 14px;line-height: 22px;color: #a1a2a5;background-color: #f6f6f7;">
                        Subtotal<br>
                        Deposit
                    </td>
                    <td class="width84 subTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;font-size: 14px;line-height: 22px;color: #a1a2a5;background-color: #f6f6f7;"><span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">Subtotal: </span><span class="amount" style="color: #54565c;">$1,400</span><br>
                        <span class="desktopHide" style="display: none;font-size: 0;max-height: 0;width: 0;line-height: 0;overflow: hidden;mso-hide: all;">
                            Deposit:
                        </span>
                        <span class="amount" style="color: #54565c;">$400</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="width312 eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 312px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <strong>Total</strong> due by Sep 10, 2014
                    </td>
                    <td class="width84 eTotal alignRight" style="{{ $resetMargin }}padding-top: 14px;padding-bottom: 14px;padding-left: 16px;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;text-align: right;vertical-align: top;width: 84px;border-bottom: 1px solid #ebebeb;font-size: 14px;line-height: 19px;color: #54565c;background-color: #ffffff;">
                        <span class="amount" style="color: #3cbff3;font-size: 16px;font-weight: bold;">$1,000</span>
                    </td>
                </tr>
            </table>
        </td>
        <!-- end invoice content-->
    </tr>
    <tr>
        <td class="eBody pdBt16" style="{{ $resetMargin }}padding-top: 12px;padding-bottom: 16px;padding-left: 12px;padding-right: 12px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;width: 512px;color: #54565c;background-color: #ffffff;border-left: 4px solid #b3bdca;border-right: 4px solid #b3bdca;">
            <table border="0" align="right" cellpadding="0" cellspacing="0" class="messageOptions alignRight" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;text-align: right;">
                <tr>
                    <td class="moBtn pdRg16" style="{{ $resetMargin }}padding-top: 0;padding-bottom: 0;padding-left: 0;padding-right: 16px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;"><table border="0" align="right" cellpadding="0" cellspacing="0" class="defaultBtn" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;">
                            <tr>
                                <td width="4" height="8" align="left" valign="top" class="btnLfTp" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #235daa;line-height: 100%;width: 4px;height: 4px;text-align: left;vertical-align: top;font-size: 0 !important;">
                                    <img src="images/mainBtn_lftp.png" width="4" height="4" style="margin-top: 0;margin-left: 0;margin-right: auto;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                                $emptyCell
                                <td width="4" height="8" align="right" valign="top" class="btnRgTp" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #235daa;line-height: 100%;width: 4px;height: 4px;text-align: right;vertical-align: top;font-size: 0 !important;">
                                    <img src="images/mainBtn_rgtp.png" width="4" height="4" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                            </tr>
                            <tr>
                                $emptyCell
                                <td class="btnMain" style="{{ $resetMargin }}padding-top: 8px;padding-bottom: 8px;padding-left: 18px;padding-right: 18px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #235daa;height: 20px;font-size: 18px;line-height: 20px;mso-line-height-rule: exactly;text-align: center;vertical-align: middle;">
                                    <a href="#" style="{{ $resetPadding }}display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;color: #ffffff;margin-left: auto;margin-right: 0;">
                                        <span style="text-decoration: none;color: #ffffff;">
                                            Contact Us
                                        </span>
                                    </a>
                                </td>
                                $emptyCell
                            </tr>
                            <tr>
                                <td align="left" valign="bottom" class="btnLfBt" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #235daa;line-height: 100%;width: 4px;height: 4px;text-align: left;vertical-align: bottom;font-size: 0 !important;">
                                    <img src="images/mainBtn_lfbt.png" width="4" height="4" style="margin-top: 0;margin-left: 0;margin-right: auto;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                                $emptyCell
                                <td align="right" valign="bottom" class="btnRgBt" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #235daa;line-height: 100%;width: 4px;height: 4px;text-align: right;vertical-align: bottom;font-size: 0 !important;">
                                    <img src="images/mainBtn_rgbt.png" width="4" height="4" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                            </tr>
                        </table></td>
                    <td class="moBtn" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="mainBtn" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}mso-table-lspace: 0pt;mso-table-rspace: 0pt;border-collapse: collapse;border-spacing: 0;">
                            <tr>
                                <td width="4" height="8" align="left" valign="top" class="btnLfTp" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #7fbe56;line-height: 100%;width: 4px;height: 4px;text-align: left;vertical-align: top;font-size: 0 !important;">
                                    <img src="images/mainBtn_lftp.png" width="4" height="4" style="margin-top: 0;margin-left: 0;margin-right: auto;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                                $emptyCell
                                <td width="4" height="8" align="right" valign="top" class="btnRgTp" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #7fbe56;line-height: 100%;width: 4px;height: 4px;text-align: right;vertical-align: top;font-size: 0 !important;">
                                    <img src="images/mainBtn_rgtp.png" width="4" height="4" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                            </tr>
                            <tr>
                                $emptyCell
                                <td class="btnMain" style="{{ $resetMargin }}padding-top: 8px;padding-bottom: 8px;padding-left: 18px;padding-right: 18px;border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #7fbe56;height: 20px;font-size: 18px;line-height: 20px;mso-line-height-rule: exactly;text-align: center;vertical-align: middle;">
                                    <a href="#" style="{{ $resetPadding }}display: inline-block;text-decoration: none;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;color: #ffffff;margin-left: auto;margin-right: 0;font-weight: bold;">
                                        <span style="text-decoration: none;color: #ffffff;">Download Invoice</span>
                                    </a>
                                </td>
                                $emptyCell
                            </tr>
                            <tr>
                                <td align="left" valign="bottom" class="btnLfBt" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #7fbe56;line-height: 100%;width: 4px;height: 4px;text-align: left;vertical-align: bottom;font-size: 0 !important;">
                                    <img src="images/mainBtn_lfbt.png" width="4" height="4" style="margin-top: 0;margin-left: 0;margin-right: auto;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                                $emptyCell
                                <td align="right" valign="bottom" class="btnRgBt" style="{{ $resetMargin }}{{ $resetPadding }}border-collapse: collapse;border-spacing: 0;-webkit-text-size-adjust: none;font-family: Arial, Helvetica, sans-serif;background-color: #7fbe56;line-height: 100%;width: 4px;height: 4px;text-align: right;vertical-align: bottom;font-size: 0 !important;">
                                    <img src="images/mainBtn_rgbt.png" width="4" height="4" style="margin-top: 0;margin-left: auto;margin-right: 0;margin-bottom: 0;{{ $resetPadding }}height: 4px;width: 4px;line-height: 0 !important;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;font-size: 0 !important;">
                                </td>
                            </tr>
                        </table></td>
                </tr>
            </table></td>
    </tr>
    <!-- end .eBody -->

@stop