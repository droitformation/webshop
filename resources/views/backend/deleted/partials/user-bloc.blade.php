<div class="{{ $user->trashed() ? 'isTrashed' : 'isNotTrashed' }}">
    <p><strong>{{ $user->name }}</strong></p>
    <p><i>{{ $user->email }}</i></p>
</div>