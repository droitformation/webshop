<div class="edit_content">
    <button class="btn btn-danger btn-xs deleteActionNewsletter deleteContentBloc deleteContentBlocArret pull-right" data-id="{{ $bloc->id }}" data-action="{{ isset($bloc->arret) ? $bloc->arret->reference : '' }}" type="button">&nbsp;×&nbsp;</button>
    <!-- Arret -->
    @include('emails.newsletter.send.arret', ['arret' => $bloc->arret])
    <!-- End Arret -->
</div>
