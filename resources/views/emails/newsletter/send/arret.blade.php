<!-- Bloc -->
<?php $width = isset($isEdit) ? 560 : 600; ?>
@if(isset($bloc->arret))

    <table border="0" width="{{ $width }}" align="center" cellpadding="0" cellspacing="0" class="tableReset {{ $bloc->arret->dumois ? 'alert-dumois' : '' }}">
        <tr bgcolor="ffffff"><td height="35"></td></tr><!-- space -->
        <tr align="center" class="resetMarge">
            <td class="resetMarge">
                <!-- Bloc content-->
                <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="tableReset contentForm">
                    <tr>
                        <td valign="top" width="375" class="resetMarge">
                            <h3 class="mainTitle" style="text-align: left;font-family: sans-serif;">{{ $bloc->arret->dumois ? 'Arrêt du mois : ' : '' }}{{ $bloc->arret->reference }} du {{ $bloc->arret->pub_date->formatLocalized('%d %B %Y') }}</h3>
                            <p class="abstract">{!! $bloc->arret->abstract !!}</p>
                            <div>{!! $bloc->arret->pub_text !!}</div>
                            <p><a href="{{ secure_asset(config('newsletter.path.arret').$bloc->arret->file) }}">Télécharger en pdf</a></p>
                        </td>
                        <td width="25" height="1" class="resetMarge" valign="top" style="font-size: 1px; line-height: 1px;margin: 0;padding: 0;"></td><!-- space -->
                        <td align="center" valign="top" width="160" class="resetMarge">
                           @if(!$bloc->arret->categories->isEmpty() )
                               @include('emails.newsletter.send.partials.categories',['categories' => $bloc->arret->categories])
                           @endif
                        </td>
                    </tr>
                </table>
                <!-- Bloc content-->
            </td>
        </tr>

        {!! $bloc->arret->analyses->isEmpty() ? row() : '' !!}

        <tr>
            <td align="center">
                <!-- Analyses -->
                @include('emails.newsletter.send.partials.analyses', ['arret' => $bloc->arret, 'isEdit' => isset($isEdit) ? true : false])
                <!-- End Analyses -->
            </td>
        </tr>

        {!! !$bloc->arret->analyses->isEmpty() ? row() : '' !!}

    </table>
    <!-- End bloc -->
@endif