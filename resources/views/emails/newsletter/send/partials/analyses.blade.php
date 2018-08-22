@if(!$arret->analyses->isEmpty())
    <tr>
        <td align="center">

            @component('emails.newsletter.send.partials.tablebloc',['direction' => 'right'])
                @slot('picto')
                    <?php $site = isset($campagne->newsletter->site) ? $campagne->newsletter->preview.'/'.$campagne->newsletter->site->slug : 'pubdroit'; ?>

                    <a target="_blank" href="{{ $site }}/page/jurisprudence">
                        <?php $slug = $campagne->newsletter->site_id ? '/'.$campagne->newsletter->site->slug.'/' : ''; ?>
                        <img border="0" style="max-width: 130px;" alt="Analyses" src="{{ secure_asset(config('newsletter.path.categorie').$slug.'analyse.jpg') }}">
                    </a>
                @endslot

                @slot('content')
                    @foreach($arret->analyses as $i => $analyse)
                        <table border="0" width="375" align="left" cellpadding="0" cellspacing="0" class="resetTable">
                            @if( $arret->analyses->count() > 1 && $arret->analyses->count() > $i+1)
                                <tr bgcolor="ffffff"><td colspan="3" height="15" class=""></td></tr><!-- space -->
                            @endif
                            <tr>
                                <td valign="top" width="375" class="resetMarge contentForm">
                                    <h3 style="text-align: left;font-family: sans-serif;">
                                        <?php $title = isset($campagne->newsletter) ? $campagne->newsletter->comment_title : 'Commentaire'; ?>
                                        {{ $title }} de l'arrêt {{ $arret->reference }}
                                    </h3>
                                    <!-- Authors -->
                                    @include('emails.newsletter.send.partials.authors')
                                    <!-- End Authors -->
                                    <p class="abstract">{!! $analyse->abstract !!}</p>
                                    <p><a target="_blank" href="{{ secure_asset(config('newsletter.path.analyse').$analyse->file) }}">Télécharger en pdf</a></p>
                                </td>
                            </tr>
                            @if( $arret->analyses->count() > 1 && $arret->analyses->count() > $i+1)
                                <tr bgcolor="ffffff"><td colspan="3" height="15" class=""></td></tr><!-- space -->
                            @endif
                        </table>
                    @endforeach
                @endslot
            @endcomponent

        </td>
    </tr>

@endif