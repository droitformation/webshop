<div class="edit_content">
    <button class="btn btn-danger btn-xs deleteActionNewsletter deleteContentBloc deleteContentBlocArret pull-right" data-id="{{ $bloc->id }}" data-action="{{ isset($bloc->colloque) ? $bloc->colloque->titre : '' }}" type="button">&nbsp;×&nbsp;</button>
    @include('emails.newsletter.send.colloque', ['isEdit' => true])
</div>
