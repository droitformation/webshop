<?php  $cats = implode(' ',$arret->allcats); ?>
<div class="arret <?php echo $cats; ?> clear">
    <div class="row">
        <div class="col-md-3 listCat text-center">
            @if(!$arret->arrets_categories->isEmpty())
                @foreach($arret->arrets_categories as $categorie)
                    <img style="max-width: 140px;" border="0" alt="{{ $categorie->title }}" src="<?php echo asset('files/pictos/bail/'.$categorie->image) ?>">
                    <p><small>{{ $categorie->title }}</small></p>
                @endforeach
            @endif
        </div>
        <div class="col-md-9">
            <div class="post">
                <div class="post-title">
                    <h3>{{ $arret->humanTitle }}</h3>
                    <p>{{ $arret->abstract }}</p>
                </div><!--END POST-TITLE-->
                <div class="post-entry">
                    <a class="anchor" name="{{ $arret->reference }}"></a>
                    {!! $arret->parsedText !!}

                    @if(!empty($arret->file ) && File::exists(public_path('files/arrets/'.$arret->file)))
                        <p><a target="_blank" href="{{ asset('files/arrets/'.$arret->file) }}">Télécharger en pdf &nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i></a></p>
                    @endif
                </div>
            </div><!--END POST-->
        </div>

    </div>
</div>