<div class="post">
    <div class="post-title">
        <?php $lien = (isset($bloc->lien) && !empty($bloc->lien) ? $bloc->lien : url('/') ); ?>
        <a target="_blank" href="<?php echo $lien; ?>">
            <img style="max-width: 560px;margin: 0 auto 20px auto; display: block;" alt="" src="{{ asset('files/uploads/'.$bloc->image) }}" />
        </a>

        @if(!empty($bloc->titre))
            <h2 class="title">{{ $bloc->titre }}</h2>
        @endif

    </div><!--END POST-TITLE-->
    <div class="post-entry">
        {!! $bloc->contenu !!}
    </div>
    <span class="clear"></span>
 </div>
