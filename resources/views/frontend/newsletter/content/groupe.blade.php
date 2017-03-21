@if(isset($bloc->groupe) && !$bloc->groupe->arrets->isEmpty())

    <div class="row">
        <div class="col-md-9">
            <h3>{{ $bloc->groupe->categorie->title }}</h3>
        </div>
        <div class="col-md-3">
            <img style="max-width: 130px;" border="0" src="{{ secure_asset(config('newsletter.path.categorie').$bloc->groupe->categorie->image) }}" alt="{{ $bloc->groupe->categorie->title }}" />
        </div>
    </div>

    @foreach($bloc->groupe->arrets as $arret)
        <?php $arret->load('categories');  ?>
        <div class="row">
            <div class="col-md-9">
                <h2>{{ $arret->reference }} du {{ utf8_encode($arret->pub_date->formatLocalized('%d %B %Y')) }}</h2>
                <p>{!! $arret->abstract !!}</p>
                {!! $arret->pub_text !!}

                @if(isset($arret->file))
                    <p><a target="_blank" href="{{ secure_asset(config('newsletter.path.arret').$arret->file) }}">Télécharger en pdf</a></p>
                @endif

            </div>
            <div class="col-md-3">

                @if(!$arret->categories->isEmpty())
                    @foreach($arret->categories as $categorie)
                        <a target="_blank" href="{{ config('newsletter.link.arret') }}#{{ $bloc->reference }}">
                            <img style="max-width: 130px;" border="0" alt="{{ $categorie->title }}" src="{{ secure_asset(config('newsletter.path.categorie').$categorie->image) }}">
                        </a>
                    @endforeach
                @endif

            </div>
            <div class="clear"></div>
        </div>
        
        @if(!$arret->analyses->isEmpty())
            @foreach($arret->analyses as $analyse)
                <div class="row">
                    <div class="col-md-9">
                        <h4>Commentaire l'arrêt {{ $arret->reference }}</h4>

                        @if(!$analyse->authors->isEmpty())
                            @foreach($analyse->authors as $author)

                                <div class="media">
                                    @if(File::exists(config('newsletter.path.author').$author->author_photo))
                                        <div class="media-left">
                                            <a href="#" style="display: block; width: 65px; margin-bottom: 10px;">
                                                <img class="media-object" alt="{{ $author->name }}" src="{{ secure_asset(config('newsletter.path.author').$author->author_photo) }}">
                                            </a>
                                        </div>
                                    @endif
                                    <div class="media-body">
                                        <h4><i>{{ $author->name }}</i></h4>
                                        <p>{{ $author->occupation }}</p>
                                    </div>
                                </div>

                            @endforeach
                        @endif

                        {!! $analyse->abstract !!}
                        <p><a href="{{ secure_asset(config('newsletter.path.analyse').$analyse->file) }}">Télécharger en pdf</a></p>
                    </div>
                    <div class="col-md-3">
                        <a target="_blank" href="{{ config('newsletter.link.analyse') }}">
                            <?php $slug = $campagne->newsletter->site_id ? '/'.$campagne->newsletter->site->slug.'/' : ''; ?>
                            <img border="0" style="max-width: 130px;" alt="Analyses" src="{{ secure_asset(config('newsletter.path.categorie').$slug.'analyse.jpg') }}">
                        </a>
                    </div>
                    <div class="clear"></div>
                </div>
            @endforeach
        @endif

        
    @endforeach
@endif


