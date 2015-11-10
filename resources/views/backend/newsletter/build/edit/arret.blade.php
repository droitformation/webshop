<div class="edit_content">

    <!-- Bloc content-->
    <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
        <tr bgcolor="ffffff">
            <td colspan="3" height="35">
                <div class="pull-right btn-group btn-group-xs">
                    <button class="btn btn-danger deleteContent deleteContentBloc" data-id="{{ $bloc->idItem }}" data-action="{{ $bloc->reference }}" type="button">&nbsp;×&nbsp;</button>
                </div>
            </td>
        </tr><!-- space -->
        <tr>
            <td valign="top" width="375" class="resetMarge contentForm">
                <div>
                    <?php
                        $title = ($bloc->dumois ? 'Arrêt du mois : ' : '');
                        setlocale(LC_ALL, 'fr_FR.UTF-8');
                    ?>
                    <h3 style="text-align: left;">{{ $title }}{{ $bloc->reference }} du {{ $bloc->pub_date->formatLocalized('%d %B %Y') }}</h3>
                    <p class="abstract">{!! $bloc->abstract !!}</p>
                    <div>{!! $bloc->pub_text !!}</div>
                    <p><a href="{{ asset('files/arrets/'.$bloc->file) }}">Télécharger en pdf</a></p>

                </div>
            </td>
            <td width="25" class="resetMarge"></td><!-- space -->
            <td align="center" valign="top" width="160" class="resetMarge">
                <!-- Categories -->
                <div class="resetMarge">
                    <?php
                    if(!$bloc->arrets_categories->isEmpty() )
                    {
                        foreach($bloc->arrets_categories as $categorie)
                        {
                            echo '<a target="_blank" href="'.url('jurisprudence').'#'.$bloc->reference.'"><img width="130" border="0" alt="'.$categorie->title.'" src="'.asset('pictos/'.$categorie->image).'"></a>';
                        }
                    }
                    ?>
                </div>
            </td>
        </tr>
        <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr><!-- space -->
    </table>
    <!-- Bloc content-->

    @if(isset($bloc->analyses))
        <!-- Bloc content-->
        <table border="0" width="560" align="center" cellpadding="0" cellspacing="0" class="resetTable">
            <tr bgcolor="ffffff">
                <td colspan="3" height="35"></td>
            </tr><!-- space -->
            <tr>
                <td valign="top" width="375" class="resetMarge contentForm">
                    <?php $i = 1; ?>
                    @foreach($bloc->analyses as $analyse)
                        <table border="0" width="375" align="left" cellpadding="0" cellspacing="0" class="resetTable">
                            <tr>
                                <td valign="top" width="375" class="resetMarge contentForm">
                                    <?php setlocale(LC_ALL, 'fr_FR.UTF-8');  ?>
                                    <h3 style="text-align: left;">Analyse de l'arrêt {{ $bloc->reference }}</h3>

                                    @if(isset($analyse->analyse_authors))
                                        @foreach($analyse->analyse_authors as $analyse_authors)
                                            <table border="0" width="375" align="left" cellpadding="0" cellspacing="0" class="resetTable">
                                                <tr>
                                                    <td valign="top" width="60" class="resetMarge">
                                                        <img width="60" border="0" alt="{{ $analyse_authors->name }}" src="{{ asset('authors/'.$analyse_authors->photo) }}">
                                                    </td>
                                                    <td valign="top" width="10" class="resetMarge"></td>
                                                    <td valign="top" width="305" class="resetMarge">
                                                        <h3 style="text-align: left;">{{ $analyse_authors->name }}</h3>
                                                        <p style="text-align: left;">{{  $analyse_authors->occupation }}</p>
                                                    </td>
                                                </tr>
                                                <tr bgcolor="ffffff"><td colspan="3" height="15" class=""></td></tr><!-- space -->
                                            </table>
                                       @endforeach
                                    @endif

                                    <p class="abstract">{!! $analyse->abstract !!}</p>
                                    <p><a href="{{ asset('files/analyses/'.$analyse->file) }}">Télécharger en pdf</a></p>
                                </td>
                            </tr>

                            @if( $bloc->analyses->count() > 1 && $bloc->analyses->count() > $i)
                                <tr bgcolor="ffffff"><td colspan="3" height="35" class=""></td></tr><!-- space -->
                            @endif

                            <?php $i++; ?>
                        </table>
                    @endforeach

                </td>
                <td width="25" class="resetMarge"></td><!-- space -->
                <td align="center" valign="top" width="160" class="resetMarge">
                    <!-- Categories -->
                    <div class="resetMarge">
                        <a target="_blank" href="{{ url('jurisprudence') }}">
                            <img width="130" border="0" alt="Analyse" src="{{ asset('images/analyse.png') }}">
                        </a>
                    </div>
                </td>
            </tr>
            <tr bgcolor="ffffff"><td colspan="3" height="35" class="blocBorder"></td></tr><!-- space -->
        </table>
        <!-- Bloc content-->
    @endif

</div>
