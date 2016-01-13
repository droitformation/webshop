<div class="analyses">
    <div class="row">
        <div class="col-md-3 last listCat listAnalyse">
            <img style="max-width: 140px;" border="0" alt="Analyses" src="<?php echo asset('files/pictos/bail/analyse.jpg') ?>">
        </div>
        <div class="col-md-9">
            @if(!empty($analyses))
                @foreach($analyses as $analyse)

                    <?php  $cats = implode(' ',$analyse->allcats); ?>
                    <div class="analyse arret <?php echo $cats; ?> clear">
                        <div class="post">
                            <div class="post-title">
                                <a class="anchor_top" name="analyse_{{ $analyse->id }}"></a>
                                <h3 class="title">Analyse de {{ $analyse->authors }}</h3>
                                @if(!$analyse->analyses_arrets->isEmpty())
                                    <ul>
                                        @foreach($analyse->analyses_arrets as $arret)
                                            <li>
                                                <a href="#{{ $arret->reference }}">{{ $arret->reference.' du '.$arret->pub_date->formatLocalized('%d %B %Y') }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <p>{!! $analyse->abstract !!}</p>
                            </div><!--END POST-TITLE-->
                            <div class="post-entry">
                                @if(!empty($analyse->file ) && File::exists(public_path('files/analyses/'.$analyse->file)))
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
            @else
                <p>&nbsp;</p>
            @endif

        </div>

    </div>
    <div class="divider-border-nofloat"></div>
</div>
