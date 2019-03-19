@if(isset($bloc->arret))

<div class="row">
    <div class="col-md-9">
        <h2 class="text-left">{{ $bloc->arret->reference }} du {{ utf8_encode($bloc->arret->pub_date->formatLocalized('%d %B %Y')) }}</h2>
        <p>{!! $bloc->arret->abstract !!}</p>

        {!! $bloc->arret->pub_text !!}
        @if(isset($bloc->arret->file))
            <p><a target="_blank" href="{{ secure_asset(config('newsletter.path.arret').$bloc->arret->file) }}">Télécharger en pdf</a></p>
        @endif
    </div>
    <div class="col-md-3">
        @if(!$bloc->arret->categories->isEmpty() )
            @foreach($bloc->arret->categories as $categorie)
                <a target="_blank" href="{{ url(config('newsletter.link.arret')) }}#{{ $bloc->reference }}">
                    <img style="max-width: 130px;" border="0"  alt="{{ $categorie->title }}" src="{{ secure_asset(config('newsletter.path.categorie').$categorie->image) }}">
                </a>
            @endforeach
        @endif
    </div>
    <div class="clear"></div>
</div>

@if(!$bloc->arret->analyses->isEmpty())
    @foreach($bloc->arret->analyses as $analyse)
        <div class="row">
            <div class="col-md-9">
                <h4>Commentaire l'arrêt {{ $bloc->arret->reference }}</h4>

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
                                <p>{!! $author->occupation !!}</p>
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

@endif