@if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())

    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-4">Colloque</th>
                <th class="col-md-1">N°</th>
                <th class="col-md-2">Date</th>
                <th class="col-md-2">Envoyé le</th>
                <th class="col-md-2">Montant</th>
                <th class="text-right col-md-1">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user->inscriptions as $inscription)
                <?php $inscription->load('colloque','rappels'); ?>
                <tr class="mainRow">
                    <td>
                        <a class="collapse_anchor" data-toggle="collapse" href="#inscription_no_{{ $inscription->id }}">
                            <i class="fa fa-arrow-circle-right"></i>{{ $inscription->colloque->titre }}
                        </a>
                    </td>
                    <td><strong>{{ $inscription->inscription_no }}</strong></td>
                    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
                    <td>
                        @if($inscription->send_at)
                            <span class="fa fa-paper-plane"></span> &nbsp;{{ $inscription->send_at->formatLocalized('%d %b %Y') }}
                        @endif
                    </td>
                    <td>{{ $inscription->price_cents }} CHF</td>
                    <td class="text-right"><span class="label label-{{ $inscription->status_name['color'] }}">{{ $inscription->status_name['status'] }}</span></td>
                </tr>
                <tr>
                    <td colspan="6" class="nopadding">

                        <!-- Inscription details -->
                        <div class="collapse customCollapse" id="inscription_no_{{ $inscription->id }}">
                            <div class="inscription_wrapper">

                                <!-- Inscription dependences -->
                                <div class="row">
                                    <div class="col-md-2">
                                        <h4>Payement</h4>
                                        @if($inscription->payed_at)
                                            <h1 class="label label-success" style="font-size: 90%;">Payé le {{ $inscription->payed_at->format('d/m/Y') }}</h1>
                                        @else
                                            <h1 class="label label-default" style="font-size: 90%;">En attente</h1>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <h4>Documents</h4>
                                        @include('backend.users.inscription.documents')
                                    </div>
                                    <div class="col-md-5">
                                        <h4>Options</h4>
                                        @include('backend.users.inscription.options')
                                    </div>
                                </div>
                                <!--END Inscription dependences -->

                                <!-- Inscription edit,send,rappels -->
                                <div class="row">
                                    <div class="col-md-2 line-spacer">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-5 line-spacer">
                                        @if(!empty($inscription->colloque->annexe))
                                            <a href="{{ url('admin/inscription/generate/'.$inscription->id) }}" class="btn btn-sm btn-warning">Regénérer les documents</a>
                                        @endif
                                        @if(!$inscription->doc_attestation)
                                            <a href="{{ url('admin/attestation/inscription/'.$inscription->id) }}" class="btn btn-sm btn-green">Attestation</a>
                                        @endif
                                    </div>
                                    <div class="col-md-5 line-spacer text-right">
                                        <button type="button" class="btn btn-sm btn-inverse" data-toggle="modal" data-target="#sendInscription_{{ $inscription->id }}">
                                            Envoyer l'inscription
                                        </button>
                                    </div>
                                </div>
                                <!-- END Inscription edit,send,rappels -->

                                @include('backend.users.modals.send', ['inscription' => $inscription]) <!-- Modal send inscription -->
                                @include('backend.users.modals.edit', ['inscription' => $inscription]) <!-- Modal edit inscription -->

                            </div>
                        </div>
                        <!-- END Inscription details -->

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <p>Encore aucune inscription</p>
@endif