<div class="analyses">

    <div class="row">
        <div class="col-md-9">

            @foreach($analyses as $analyse)

                <div class="analyse arret {{ $analyse->filter }} y{{ $analyse->pub_date->year }} clear">
                    <div class="post">
                        <div class="post-title">
                            <a class="anchor_top" name="analyse_{{ $analyse->id }}"></a>
                            <h3 class="title">Analyse de {{ $analyse->authors->implode('name', ', ') }}</h3>

                            @if(!$analyse->arrets->isEmpty())
                                <ul>
                                    @foreach($analyse->arrets as $arret)
                                        <li>
                                            <a href="#{{ $arret->reference }}">{{ $arret->reference.' du '.$arret->pub_date->formatLocalized('%d %B %Y') }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <p>{!! $analyse->abstract !!}</p>
                        </div><!--END POST-TITLE-->
                        <div class="post-entry">
                            @if($analyse->document)
                                <p>
                                    <a target="_blank" href="{{ asset('files/analyses/'.$analyse->file) }}">
                                        Télécharger cette analyse en PDF &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </p>
                            @endif
                        </div>

                    </div>
                </div>

            @endforeach
        </div>
        <div class="col-md-3 last listCat listAnalyse text-center">
            <img style="max-width: 140px;" border="0" alt="Analyses" src="{{ asset('files/pictos/matrimonial/analyse.jpg') }}">
        </div>
    </div>
    <div class="divider-border-nofloat"></div>
</div>
