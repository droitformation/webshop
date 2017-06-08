<form action="{{ url('subscribe') }}" method="POST" class="form" id="subscribe">
    {!! csrf_field() !!}
    <div class="form-group">
        <label class="control-label">Votre email</label>
        <div class="input-group">
            <input type="text" class="form-control" name="email" value="{{ old('email') or '' }}">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Inscription</button>
            </span>
        </div>
    </div>
    <input type="hidden" name="newsletter_id" value="{{ $newsletter_id }}">
    <input type="hidden" name="site_id" value="{{ $site->id }}">
</form>

