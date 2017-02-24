<div class="analyses">
    <div class="row">
        <div class="col-md-3 last listCat listAnalyse">
            <img style="max-width: 140px;" border="0" alt="Analyses" src="<?php echo secure_asset('files/pictos/bail/analyse.jpg') ?>">
        </div>
        <div class="col-md-9">

            @foreach($analyses as $analyse)

                <div class="analyse arret {{ $analyse->filter }} y{{ $analyse->pub_date->year }} clear">
                    <div class="post">
                        <div class="post-title">
                            <a class="anchor_top" name="analyse_{{ $analyse->id }}"></a>
                            <h3 class="title">Analyse de {{ $analyse->authors->implode('name', ', ') }}</h3>
                            <p>{!! $analyse->abstract !!}</p>
                        </div><!--END POST-TITLE-->
                        <div class="post-entry">
                            @if($analyse->document)
                                <p>
                                    <a target="_blank" href="{{ secure_asset('files/analyses/'.$analyse->file) }}">
                                        Télécharger cette analyse en PDF &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
                                    </a>
                                </p>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach

        </div>

    </div>
    <div class="divider-border-nofloat"></div>
</div>
