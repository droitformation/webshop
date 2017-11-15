<form action="{{ url('subscribe') }}" method="POST" class="form" id="subscribe">{!! csrf_field() !!}
    <div class="form-group">
        <label class="control-label">Votre email</label>
        <div class="input-group">
            <input type="text" class="form-control" id="subscribeNewsletter" name="email" value="{{ old('email') or '' }}">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Inscription</button>
            </span>
        </div>
    </div>
    <input type="hidden" name="newsletter_id" value="{{ $newsletter->id }}">
    <input type="hidden" name="site_id" value="{{ $newsletter->site_id }}">
    <input type="hidden" name="return_path" value="{{ $return_path or old('return_path') or '' }}">
    <input type="hidden" name="honey_pot" id="honey_pot" value="">
</form>

