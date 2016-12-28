<tr {!! ($inscription->group_id ? 'class="isGoupe"' : '') !!}>
    <td>
        @if(!$inscription->group_id)
            <a class="btn btn-sky btn-sm" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}"><i class="fa fa-edit"></i></a>
            @include('backend.users.modals.edit', ['inscription' => $inscription]) <!-- Modal edit inscription -->

            <a class="btn btn-sky btn-sm" data-toggle="modal" data-target="#singleInscription_{{ $inscription->id }}"><i class="fa fa-eye"></i></a>
            @include('backend.users.modals.inscription', ['inscription' => $inscription])

        @endif
    </td>
    <td>

        @if(isset($inscription->inscrit))
            <?php $adresse = $inscription->inscrit->adresses->where('type',1)->first();?>
            @if(isset($adresse))
                <p><strong>{{ $adresse->civilite_title }}</strong></p>
            @endif
            <p><a href="{{ url('admin/user/'.$inscription->inscrit->id) }}">{{ $adresse ? $adresse->name : '' }}</a></p>
            <p>{{ $inscription->inscrit->email }}</p>
        @else
            <p><span class="label label-warning">Utilisateur ou adresse non trouvé ID: {{ $inscription->group_id or $inscription->user_id }}</span></p>
        @endif

        @if($inscription->group_id)
            <br/><a class="btn btn-info btn-xs" data-toggle="modal" data-target="#editGroup_{{ $inscription->groupe->id }}">Changer le détenteur</a>
            @include('backend.inscriptions.modals.change', ['group' => $inscription->groupe]) <!-- Modal edit group -->
        @endif

    </td>
    <td>
        @if($inscription->group_id)
            <?php $group = $inscription->groupe; ?>
            @if($group->inscriptions)
                @foreach($group->inscriptions as $inscription)
                    <div class="media">
                        <div class="media-left">
                            <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
                                <input type="hidden" name="_method" value="DELETE">
                                <a class="btn btn-sky btn-xs" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}"><i class="fa fa-edit"></i></a>
                                <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-xs deleteAction">X</button>
                            </form>
                            @include('backend.users.modals.edit', ['inscription' => $inscription]) <!-- Modal edit inscription -->
                        </div>
                        <div class="media-body">
                            <p><strong>{!! $inscription->participant->name !!}</strong></p>
                            <p>
                                {{ $inscription->inscription_no }} &nbsp;
                                @include('backend.partials.toggle', ['id' => $inscription->id])
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif

            <br/><a class="btn btn-success btn-xs" data-toggle="modal" data-target="#addToGroup_{{ $inscription->groupe->id }}">Ajouter un participant</a>
            @include('backend.inscriptions.modals.add', ['group' => $inscription->groupe, 'colloque' => $inscription->colloque]) <!-- Modal add to group -->
        @else
            <strong>{{ $inscription->inscription_no }}</strong>&nbsp;
            @include('backend.partials.toggle', ['id' => $inscription->id])
        @endif
    </td>
    <td>{{ $inscription->group_id ? $group->price : $inscription->price_cents }} CHF</td>
    <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
    <td>
        <?php $model = ($inscription->group_id ? 'group' : 'inscription'); ?>
        <?php $item  = ($inscription->group_id ? $inscription->groupe : $inscription); ?>
        @include('backend.inscriptions.partials.payed',['model' => $model, 'item' => $item])
    </td>
    <td class="text-right">
        @if(!$inscription->group_id)
            <form action="{{ url('admin/inscription/'.$inscription->id) }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
                <input type="hidden" name="_method" value="DELETE">
                <button data-what="Désinscrire" data-action="N°: {{ $inscription->inscription_no }}" class="btn btn-danger btn-sm deleteAction">X</button>
            </form>
        @else
            <form action="{{ url('admin/inscription/destroygroup/'.$inscription->groupe->id) }}" method="POST" class="form-horizontal">{!! csrf_field() !!}
                <button data-what="Supprimer" data-action="le groupe et ses inscriptions" class="btn btn-danger btn-sm deleteAction">X</button>
            </form>
        @endif
    </td>
</tr>