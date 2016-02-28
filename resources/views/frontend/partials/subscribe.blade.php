<form action="{{ url('subscribe') }}" method="POST" class="form" id="subscribe">
    {!! csrf_field() !!}
    <div class="input-group">
        <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Votre email">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Inscription</button>
        </span>
    </div>
    <input type="hidden" name="newsletter_id" value="{{ $newsletter_id }}">
</form>

