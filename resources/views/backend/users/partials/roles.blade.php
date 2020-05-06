<div class="form-group">
    <label class="control-label"><h4>Role</h4></label>&nbsp;
    <label class="radio"><input {{ $user->roles->isEmpty() ? 'checked' : '' }} type="radio" name="role" value="10"> Utilisateur simple</label>
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
    <hr>
    <div class="form-group">
        <label class="control-label"><h4>Accès spécial listes d'adresses</h4></label>&nbsp;
        <div id="access_tags" class="well well-sm">
            <ul id="access" data-id="{{ $user->id }}">
                {!! $user->access_list !!}
            </ul>
        </div>
    </div>
@endif