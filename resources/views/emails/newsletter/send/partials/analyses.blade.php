
@if(!$arret->analyses->isEmpty())
    <!-- Bloc content-->
    <?php $width = isset($isEdit) ? 560 : 600; ?>
    <table border="0" width="{{ $width }}" align="center" cellpadding="0" cellspacing="0" class="resetTable">
        <tr bgcolor="ffffff"><td height="35"></td></tr><!-- space -->
        <tr align="center">

            <td class="resetMarge">
                <!-- Bloc content-->
                <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="tableReset contentForm">
                    <tr>
                        <td valign="top" align="center" width="375" class="resetMarge contentForm">
                            @foreach($arret->analyses as $i => $analyse)
                                <table border="0" width="375" align="left" cellpadding="0" cellspacing="0" class="resetTable">
                                    <tr>
                                        <td valign="top" width="375" class="resetMarge contentForm">
                                            <h3 style="text-align: left;font-family: sans-serif;">Commentaire de l'arrêt {{ $arret->reference }}</h3>
                                            <!-- Authors -->
                                            @include('emails.newsletter.send.partials.authors')
                                            <!-- End Authors -->
                                            <p class="abstract">{!! $analyse->abstract !!}</p>
                                            <p><a href="{{ secure_asset(config('newsletter.path.analyse').$analyse->file) }}">Télécharger en pdf</a></p>
                                        </td>
                                    </tr>

                                    {!! $arret->analyses->count() > 0 && $arret->analyses->count() > $i + 1 ? row() : '' !!}

                                </table>
                            @endforeach
                        </td>
                        <td width="25" class="resetMarge"></td><!-- space -->
                        <td align="center" valign="top" width="160" class="resetMarge">
                            <?php $site = isset($campagne->newsletter->site) ? $campagne->newsletter->preview.'/'.$campagne->newsletter->site->slug : 'pubdroit'; ?>

                            <a target="_blank" href="{{ $site }}/page/jurisprudence">
                                <?php $slug = $campagne->newsletter->site_id ? '/'.$campagne->newsletter->site->slug.'/' : ''; ?>
                                <img border="0" style="max-width: 130px;" alt="Analyses" src="{{ secure_asset(config('newsletter.path.categorie').$slug.'analyse.jpg') }}">
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- Bloc content-->
@endif