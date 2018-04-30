<!-- Bloc -->
@if(isset($arret))
    <table border="0" width="600" align="center" cellpadding="0" cellspacing="0" class="tableReset {{ $arret->dumois ? 'alert-dumois' : '' }}">


    <?php $title = isset($campagne->newsletter) ? $campagne->newsletter->comment : 'Commentaire'; ?>

        @if(isset($campagne->newsletter) && $campagne->newsletter->display == 'top')
            <!-- Analyses -->
            @include('emails.newsletter.send.partials.analyses', ['arret' => $arret])
        @endif

        <tr bgcolor="ffffff"><td height="35"></td></tr><!-- space -->
        <tr align="center" class="resetMarge">
            <td class="resetMarge">
                <!-- Bloc content-->

                @component('emails.newsletter.send.partials.tablebloc',['direction' => 'right'])
                    @slot('picto')
                        @if(!$arret->categories->isEmpty() )
                            @include('emails.newsletter.send.partials.categories',['categories' => $arret->categories])
                        @endif
                    @endslot

                    @slot('content')
                        <?php $comment = isset($campagne->newsletter) ? $campagne->newsletter->comment : null; ?>
                        {!! $arret->dumois && $comment ? '<h2 style="text-align: left;font-family: sans-serif;">Commentaire</h2>' : '' !!}

                        <h3 class="mainTitle" style="text-align: left;font-family: sans-serif;">{{ $arret->dumois ? 'Arrêt du mois : ' : '' }}{{ $arret->reference }} du {{ $arret->pub_date->formatLocalized('%d %B %Y') }}</h3>
                        <p class="abstract">{!! $arret->abstract !!}</p>
                        <div>{!! $arret->pub_text !!}</div>
                        <p><a href="{{ secure_asset(config('newsletter.path.arret').$arret->file) }}">Télécharger en pdf</a></p>
                    @endslot
                @endcomponent

            </td>
        </tr>

        @if(isset($campagne->newsletter) && $campagne->newsletter->display == 'bottom')
            <!-- Analyses -->
            @include('emails.newsletter.send.partials.analyses', ['arret' => $arret, 'isEdit' => isset($isEdit) ? true : false])
        @endif

        <tr bgcolor="ffffff"><td height="35" class="blocBorder"></td></tr><!-- space -->
    </table>
    <!-- End bloc -->
@endif