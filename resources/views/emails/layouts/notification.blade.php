<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="{{ $style['body'] }}">

<table width="100%" cellpadding="0" cellspacing="0" id="mainBody">
    <tr>
        <td style="{{ $style['email-wrapper'] }}" align="center">

            <table width="600px" cellpadding="0" cellspacing="0">

                <tr><td style="height: 15px;" width="100%"></td></tr>
                <!-- Email Body -->
                <tr>
                    <td style="{{ $style['email-body'] }}" width="100%">

                        <!-- Contenu -->
                        @yield('content')
                        <!-- Fin contenu -->

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td>
                        <table style="{{ $style['email-footer'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="{{ $fontFamily }} {{ $style['email-footer_cell'] }}">
                                    <p style="{{ $style['paragraph-sub'] }}">
                                        &copy; {{ date('Y') }}
                                        <a style="{{ $style['anchor'] }}" href="{{ url('/pubdroit') }}" target="_blank">{{ config('app.name') }}</a>.
                                        Tous droits réservés.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
