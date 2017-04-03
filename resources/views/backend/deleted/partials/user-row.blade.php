
<?php $user = ($adresse && isset($adresse->user)) ? $adresse->user : $user; ?>

@if($user)
    <tr class="{{ $user->trashed() ? 'isTrashed' : 'isNotTrashed' }}" id="user_{{ $user->id }}">
        <td>
            @if($adresse)
                <div class="control-inline">
                    <input class="styled-checkbox" name="adresses[]" id="compare_{{ $adresse->id }}" type="checkbox" value="{{ $adresse->id }}">
                    <label for="compare_{{ $adresse->id }}">&nbsp;</label>
                </div>
            @endif
        </td>
        <td><span class="label label-info">Compte {{ $user->id }}</span></td>
        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            <?php $user->load('adresses_and_trashed');?>
            @if(!$user->adresses_and_trashed->isEmpty())
                @foreach($user->adresses_and_trashed as $adresse_user)
                    <div class="well well-sm {{ $adresse_user->trashed() ? 'isTrashed' : 'isNotTrashed' }}">

                        <p>
                            {!! $adresse_user->type == 1 ? '<span class="label label-info pull-left">Contact</span>' : '' !!}
                            {!! $adresse_user->livraison == 1 ? '<span class="label label-primary pull-left">Livraison</span>' : '' !!}
                            <span class="label label-default pull-right">{{ $adresse_user->id }}</span>
                        </p>

                        <div class="clearfix"></div>

                        <address>
                            <strong>{{ isset($adresse->civilite) ? $adresse->civilite->title : '' }} {{ $adresse->first_name }} {{ $adresse->last_name }}</strong><br>
                            {!! !empty($adresse->company) ? '<p>'.$adresse->company.'</p>' : ''  !!}
                            {{ $adresse_user->adresse }}<br>
                            {!! !empty($adresse_user->complement) ? $adresse_user->complement.'<br>' : '' !!}
                            {!! !empty($adresse_user->cp) ? $adresse_user->cp_trim.'<br>' : '' !!}
                            {{ $adresse_user->npa }} {{ $adresse_user->ville }}<br>
                            {{ isset($adresse_user->pays) ? $adresse_user->pays->title : '' }}
                        </address>

                        @if($adresse_user->trashed())
                        <button type="button"
                                data-user_id="{{ $user->id }}"
                                data-id="{{ $adresse_user->id }}"
                                class="btn btn-xs btn-warning pull-left restoreAdresseBtn">restaurer</button>
                        @endif

                        <button type="button"
                                data-user_id="{{ $user->id }}"
                                data-id="{{ $adresse_user->id }}"
                                class="btn btn-xs btn-danger pull-right deleteAdresseBtn">x</button>

                        <div class="clearfix"></div>
                    </div>
                @endforeach
            @endif
        </td>
        <td>
            <dl>

                @if(!$user->orders->isEmpty())
                    <dt>Commandes</dt>
                    @foreach($user->orders as $order)
                        <dd>{{ $order->order_no }}</dd>
                    @endforeach
                @endif

                @if(!$user->inscriptions->isEmpty())
                    <dt>Inscriptions</dt>
                    @foreach($user->inscriptions as $inscription)
                        <dd>{{ $inscription->inscription_no }}</dd>
                    @endforeach
                @endif

                @if(!$user->adresses_and_trashed->isEmpty())
                    @foreach($user->adresses_and_trashed as $adresse_user)
                        @include('backend.deleted.partials.items', ['adresse' => $adresse_user, 'title' => ' des adresses associés'])
                    @endforeach
                @endif

            </dl>
        </td>
        <td>
            @if($user->trashed())
                <button type="button" data-user_id="{{ $user->id }}" class="btn btn-xs btn-warning pull-left restoreAdresseBtn">restaurer compte</button>
            @endif
        </td>
    </tr>
@else
    <tr>
        <td colspan="7">Il n'existe rien</td>
    </tr>
@endif
