<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <!-- Define Charset -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!-- Responsive Meta Tag -->
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
        <title>{{ $campagne->newsletter->titre }}</title><!-- Responsive Styles and Valid Styles -->
        <link rel="stylesheet" href="<?php echo secure_asset('newsletter/css/frontend/newsletter.css'); ?>">

        <style type="text/css">

            #main{
                margin-top:20px;
            }

            #StyleNewsletter,
            #StyleNewsletterCreate{
                font-family: Arial,Helvetica,sans-serif;
                font-size: 12px;
                text-align: justify;
                line-height: 20px;
                width: 600px;
                margin: 0 auto;
            }

            #StyleNewsletterCreate{
                padding:20px;
                margin: 0 0 0 10px;
            }

            #StyleNewsletter .resetMarge ,
            #StyleNewsletterCreate .resetMarge{
                padding: 0;
                margin: 0;
            }

            #StyleNewsletter .resetTable,
            #StyleNewsletterCreate .resetTable{
                border-collapse:collapse;
                mso-table-lspace:0pt;
                mso-table-rspace:0pt;
                margin: 0;
                padding: 0;
            }

            #StyleNewsletter #sortable td,
            #StyleNewsletterCreate #sortable td{
                background: #fff;
            }

            #StyleNewsletter .header,
            #StyleNewsletterCreate .header{
                color: #ffffff;
                font-size: 18px;
                font-weight: normal;
                margin: 0;
                padding: 0;
                font-family: Helvetica, Arial, sans-serif;
            }

            #StyleNewsletter .header.headerSmall,
            #StyleNewsletterCreate .header.headerSmall{
                font-size: 15px;
            }

            #StyleNewsletter h2,
            #StyleNewsletter h4,
            #StyleNewsletter .contentForm h3,
            #StyleNewsletter .contentForm h4,
            #StyleNewsletter .contentForm p,
            #StyleNewsletter .contentForm a,
            #StyleNewsletter .contentForm ul li,
            #StyleNewsletterCreate h2,
            #StyleNewsletterCreate .contentForm h3,
            #StyleNewsletterCreate .contentForm h4,
            #StyleNewsletterCreate .contentForm p,
            #StyleNewsletterCreate .contentForm a,
            #StyleNewsletterCreate .contentForm ul li{
                margin: 0 0 10px 0;
                padding: 0;
            }

            #StyleNewsletter .abstract,
            #StyleNewsletterCreate .abstract{
                color: #666;
                font-family: Arial,Helvetica,sans-serif;
                font-size: 12px;
                font-style: italic;
                font-weight: normal;
                margin: 0 0 10px;
                padding: 0;
                text-align: justify;
            }

            #StyleNewsletter .contentForm a,
            #StyleNewsletterCreate .contentForm a{
                color: #000;
                text-decoration: underline;
            }

            #StyleNewsletter .contentForm h4 ,
            #StyleNewsletter .contentForm p ,
            #StyleNewsletter .contentForm ul li,
            #StyleNewsletterCreate .contentForm h4 ,
            #StyleNewsletterCreate .contentForm p ,
            #StyleNewsletterCreate .contentForm ul li{
                font-size:12px;
                font-weight:normal;
            }

            #StyleNewsletter  p,
            #StyleNewsletter .contentForm p,
            #StyleNewsletter .contentForm div,
            #StyleNewsletter .contentForm .content,
            #StyleNewsletterCreate  p,
            #StyleNewsletterCreate .contentForm p,
            #StyleNewsletterCreate .contentForm div,
            #StyleNewsletterCreate .contentForm .content{
                font-family: Arial,Helvetica,sans-serif;
                font-size: 12px;
                text-align: justify;
            }

            #StyleNewsletter .contentForm ul,
            #StyleNewsletterCreate .contentForm ul{
                margin-left: 5px;
            }

            #StyleNewsletter .contentForm ul li,
            #StyleNewsletterCreate .contentForm ul li{
                text-align:justify;
                font-family:Arial, Helvetica, sans-serif;
                font-size:12px;
                font-weight:normal;
                margin-bottom: 0;
            }

            #StyleNewsletter .thumbnail,
            #StyleNewsletterCreate .thumbnail{
                margin-bottom: 0;
                max-width: 130px;
                background: none;
                border-radius: 0;
                padding: 0;
            }

            #StyleNewsletter .thumbnail.big,
            #StyleNewsletterCreate .thumbnail.big{
                margin-bottom: 10px;
                max-width: 560px;
            }

            #StyleNewsletter .thumbnail.mini,
            #StyleNewsletterCreate .thumbnail.mini{
                margin-bottom: 10px;
                max-width: 130px;
            }

            #StyleNewsletter .upoadBtn a,
            #StyleNewsletterCreate .upoadBtn a{
                background: none repeat scroll 0 0 #ccc;
                border: medium none;
                border-radius: 0;
                color: #777;
                padding: 5px 10px;
            }

            #StyleNewsletter .linkGrey,#StyleNewsletter a.linkGrey,
            #StyleNewsletterCreate .linkGrey,#StyleNewsletterCreate a.linkGrey{
                color: #999;
                font-size: 11px;
                font-weight: normal;
                font-family: Helvetica, Arial, sans-serif;
            }

            #StyleNewsletter .blocBorder,
            #StyleNewsletterCreate .blocBorder{
                border-bottom:1px solid #eaeaea;
            }

            #StyleNewsletter .newsletterborder,
            #StyleNewsletterCreate .newsletterborder{
                border:1px solid #eaeaea;
            }

            #StyleNewsletter .contentForm .centerText,#StyleNewsletter p.centerText,#StyleNewsletter p.centerText a,
            #StyleNewsletterCreate .contentForm .centerText,#StyleNewsletterCreate p.centerText,#StyleNewsletterCreate p.centerText a{
                text-align: center;
            }

            #StyleNewsletter h2,
            #StyleNewsletterCreate h2{
                font-size:14px;
                font-weight:bold;
            }

            #StyleNewsletter .contentForm h3,
            #StyleNewsletter .contentForm h4,
            #StyleNewsletterCreate .contentForm h3,
            #StyleNewsletterCreate .contentForm h4
            {
                font-size:13px;
                font-weight:bold;
            }

            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            #StyleNewsletter h2, #StyleNewsletterCreate h2{  color: {{ $campagne->newsletter->color }};  }
            #StyleNewsletter .contentForm h3, #StyleNewsletter .contentForm h4{  color: {{ $campagne->newsletter->color }};  }

            #StyleNewsletter .contentForm > a.actionBtn,
            #StyleNewsletterCreate .contentForm > a.actionBtn{
                background: {{ $campagne->newsletter->color }};
                color: #fff;
            }
            #StyleNewsletter .resetTable.alert-dumois{
                border-top: 1px solid {{ $campagne->newsletter->color }};
                border-left:1px solid {{ $campagne->newsletter->color }};
                border-right:1px solid {{ $campagne->newsletter->color }};
            }

            #sortable .alert-dumois{
                border-top: 1px solid  {{ $campagne->newsletter->color }};{{ $campagne->newsletter->color }};
                border-left:1px solid  {{ $campagne->newsletter->color }};
                border-right:1px solid {{ $campagne->newsletter->color }};
            }

            #StyleNewsletter .resetTable.alert-dumois .blocBorder{
                border-bottom: 1px solid {{ $campagne->newsletter->color }};
            }

            #sortable .alert-dumois .blocBorder{
                border-bottom: 1px solid {{ $campagne->newsletter->color }};
            }

            .link_pdf a{
                color:{{ $campagne->newsletter->color }}; !important;
            }
        </style>
        <!--[if gte mso 9]>
        <style type="text/css">
            img.header-logo { width: 600px; } /* or something like that */
            ul li{
                list-style-type: disc;
            }
        </style>
        <![endif]-->
    </head>

    <body>
        <div id="StyleNewsletter">
            <!-- Main table -->
            <table border="0" width="600" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
                <!-- Main content wrapper -->

                <!-- See in browser -->
                <tr>
                    <td width="560" align="center" valign="top">
                        <table border="0" width="560" cellpadding="0" cellspacing="0" class="resetTable">
                            <tr><td height="15"></td></tr><!-- space -->
                            <tr>
                                <td align="center" class="linkGrey">
                                    Si cet email ne s'affiche pas correctement, vous pouvez le voir directement dans
                                    <a class="linkGrey" href="{{ url('/campagne/'.$campagne->id) }}">votre navigateur</a>.
                                </td>
                            </tr>
                            <tr><td height="15"></td></tr><!-- space -->
                        </table>
                    </td>
                </tr>
                <!-- End see in browser -->

                <!-- Logos -->
                @include('emails.newsletter.send.logos')
                <!-- Header -->
                @include('emails.newsletter.send.header')

                @if(isset($campagne->newsletter) && $campagne->newsletter->pdf)
                    @include('emails.newsletter.send.link')
                @endif

                <tr>
                    <td id="sortable" class="newsletterborder" style="display:block;" width="600" align="center" valign="top">

                        <!-- Main content -->
                        @yield('content')
                        <!-- Fin contenu -->
                    </td>
                </tr>
                <tr>
                    <td width="560" align="center" valign="top">
                        <!-- See in browser -->
                        <table border="0" width="600" cellpadding="0" cellspacing="0" class="tableReset">
                            <tr><td height="15"></td></tr><!-- space -->
                            <tr>
                                <td align="center" class="linkGrey">Si vous ne désirez plus recevoir cette newsletter, vous pouvez vous désinscrire à tout moment en
                                    <a href="[[UNSUB_LINK_EN]]"></a>
                                    <?php $site = isset($campagne->newsletter->site) ? $campagne->newsletter->preview.'/'.$campagne->newsletter->site->slug : 'pubdroit'; ?>
                                    <a class="linkGrey" href="{{ url($site.'/unsubscribe') }}">cliquant ici</a>.
                                </td>
                            </tr>
                            <tr><td height="15"></td></tr><!-- space bottom -->
                        </table>
                        <!-- End see in browser -->
                    </td>
                </tr>
                <!-- End main content wrapper -->

            </table>
            <!-- End main table -->
        </div>

    </body>
</html>