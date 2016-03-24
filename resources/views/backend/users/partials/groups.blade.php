<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-5">Colloque</th>
            <th class="col-md-2">Date</th>
            <th class="col-md-2">Envoyé le</th>
            <th class="col-md-2">Montant</th>
            <th class="text-right col-md-1">Statut</th>
        </tr>
    </thead>
    <tbody>

        <!-- Group start -->
        @foreach($user->inscription_groupes as $group)

            <?php $group->load('inscriptions','colloque'); ?>

            <tr class="mainRow">
                <td>
                    <a class="collapse_anchor" data-toggle="collapse" href="#inscription_no_{{ $group->id }}"><i class="fa fa-arrow-circle-right"></i>{{ $group->colloque->titre }}</a>
                </td>
                <td>{{ $group->inscriptions->first()->created_at->formatLocalized('%d %b %Y') }}</td>
                <td>
                    @if($group->inscriptions->first()->send_at)
                        <span class="fa fa-paper-plane"></span> &nbsp;{{ $group->inscriptions->first()->send_at->formatLocalized('%d %b %Y') }}
                    @endif
                </td>
                <td>{{ $group->price }} CHF</td>
                <td class="text-right">
                    <span class="label label-{{ $group->inscriptions->first()->status_name['color'] }}">{{ $group->inscriptions->first()->status_name['status'] }}</span>
                </td>
            </tr>

            <!-- Inscription details -->
            <tr>
                <td colspan="6" class="nopadding">
                    <div class="collapse customCollapse" id="inscription_no_{{ $group->id }}">
                        <div class="inscription_wrapper inscription_wrapper_group">

                            <!-- Inscription dependences -->
                            <table class="table-inscriptions">
                                <thead>
                                    <tr class="row">
                                        <th class="col-md-1"></th>
                                        <th class="col-md-2">N°</th>
                                        <th class="col-md-2">Participant</th>
                                        <th class="col-md-2">Prix</th>
                                        <th class="col-md-1">Bon</th>
                                        <th class="col-md-4">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Inscription loop -->
                                    @foreach($group->inscriptions as $inscription)

                                        <?php $inscription->load('colloque','rappels'); ?>

                                        <tr class="row">
                                            <td class="col-md-1">
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                            <td class="col-md-2"><p><strong>{{ $inscription->inscription_no }}</strong></p></td>
                                            <td class="col-md-2"><p><strong>{{ $inscription->participant->name }}</strong></p></td>
                                            <td class="col-md-2">{{ $inscription->price_cents }} CHF</td>
                                            <td class="col-md-1">
                                                @if($inscription->doc_bon)
                                                    <a target="_blank" href="{{ asset($inscription->doc_bon) }}{{ '?'.rand(0,1000) }}" class="btn btn-default btn-sm"><i class="fa fa-file"></i> &nbsp;Bon</a>
                                                @endif
                                            </td>
                                            <td class="col-md-4">
                                                <!-- Occurences -->
                                                @if(!$inscription->occurrences->isEmpty())
                                                    <h4>Conférences</h4>
                                                    <ol>
                                                        @foreach($inscription->occurrences as $occurrences)
                                                            <li>{{ $occurrences->title }}</li>
                                                        @endforeach
                                                    </ol>
                                                @endif

                                                @include('backend.users.inscription.options')
                                            </td>
                                        </tr>

                                        <!-- Modal edit inscription -->
                                        @include('backend.users.modals.edit', ['inscription' => $inscription])

                                    @endforeach

                                <!-- END Inscription loop -->
                                </tbody>
                            </table>

                            <!-- Inscription updates buttons -->
                            <div class="row">
                                <div class="col-md-2 line-spacer">
                                    @if($group->doc_facture)
                                        <a target="_blank" href="{{ asset($group->doc_facture) }}{{ '?'.rand(0,1000) }}" class="btn btn-default btn-sm"><i class="fa fa-file"></i> &nbsp;Facture</a>
                                    @endif
                                    @if($group->doc_bv)
                                        <a target="_blank" href="{{ asset($group->doc_bv) }}{{ '?'.rand(0,1000) }}" class="btn btn-default btn-sm"><i class="fa fa-file"></i> &nbsp;BV</a>
                                    @endif
                                </div>
                                <div class="col-md-7 line-spacer">
                                    <div class="btn-group">
                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#editGroup_{{ $group->id }}">Changer le détenteur</a>
                                        <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#addToGroup_{{ $group->id }}">Ajouter un participant</a>
                                        @if(!empty($group->colloque->annexe))
                                            <a href="{{ url('admin/inscription/generate/'.$inscription->id) }}" class="btn btn-sm btn-warning">Regénérer les documents</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3 line-spacer text-right">
                                    <button type="button" class="btn btn-sm btn-inverse" data-toggle="modal" data-target="#sendInscriptionGroup_{{ $group->id }}">Envoyer l'inscription</button>
                                </div>
                            </div>

                            <!-- END Inscription updates buttons -->

                            <!-- Modals add to and edit group -->
                            @include('backend.inscriptions.modals.add', ['group' => $inscription->groupe, 'colloque' => $inscription->colloque])
                            @include('backend.inscriptions.modals.change', ['group' => $group])
                            @include('backend.users.modals.sendgroup', ['group' => $inscription->groupe]) <!-- Modal send inscription -->
                            <!--END Inscription dependences -->

                        </div>
                    </div>
                </td>
            </tr>
            <!-- END Inscription details -->

        @endforeach
        <!-- Group end -->

    </tbody>
</table>
