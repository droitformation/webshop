<div class="form-group">
    <label class="control-label">Role</label>&nbsp;
    <label class="radio"><input {{ $user->roles->isEmpty() ? 'checked' : '' }} type="radio" name="role" value="0"> Utilisateur simple</label>
    @if(isset($roles))
        @foreach($roles as $role)
            <label class="radio">
                <input type="radio" data-name="{{ $role->name }}" class="roles" name="role" {{ $user->roles->contains('id',$role->id) ? 'checked' : '' }} value="{{ $role->id }}">
                {{ $role->name }}
            </label>
        @endforeach
    @endif
</div>

@if($user->roles->contains('name','Editeur') || $user->roles->contains('name','Administrateur'))
    <div id="access_tags" class="well well-sm">
        <p class="text-muted">Acces spécial listes d'adresses</p>
        <ul id="access" data-id="{{ $user->id }}">
            {!! $user->access_list !!}
        </ul>
    </div>
@endif