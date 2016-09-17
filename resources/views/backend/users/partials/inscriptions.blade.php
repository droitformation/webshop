@if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())

<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">Colloque</th>
            <th class="col-md-2">N°</th>
            <th class="col-md-2">Montant</th>
            <th class="col-md-3">Payé</th>
        </tr>
    </thead>
    <tbody>
        @foreach($user->inscriptions as $inscription)
            <?php $inscription->load('colloque','rappels'); ?>
            <tr class="mainRow">
                <td>
                    <a class="collapse_anchor" data-toggle="collapse" href="#inscription_simple_{{ $inscription->id }}">
                        <i class="fa fa-arrow-circle-right"></i>{{ $inscription->colloque->titre }}
                    </a>
                </td>
                <td>
                    <strong>{{ $inscription->inscription_no }}</strong>&nbsp;
                    @include('backend.partials.toggle', ['id' => $inscription->id])
                </td>
                <td>{{ $inscription->price_cents }} CHF</td>
                <td>@include('backend.inscriptions.partials.payed',['model' => 'inscription', 'item' => $inscription])</td>
            </tr>
            <tr>
                <td colspan="6" class="nopadding">

                    <!-- Inscription details -->
                    <div class="collapse customCollapse" id="inscription_simple_{{ $inscription->id }}">
                        <div class="inscription_wrapper">

                            <!-- Inscription single -->
                            @include('backend.users.partials.single-inscription')
                            <!--END Inscription single -->

                            <!-- Inscription edit,send,rappels -->
                            <div class="row">
                                <div class="col-md-2 line-spacer">
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                                <div class="col-md-6 line-spacer">
                                    @if(!empty($inscription->colloque->annexe))
                                        <a href="{{ url('admin/inscription/generate/'.$inscription->id) }}" class="btn btn-sm btn-warning">Regénérer les documents</a>
                                    @endif
                                    @if(!$inscription->doc_attestation)
                                        <a href="{{ url('admin/attestation/inscription/'.$inscription->id) }}" class="btn btn-sm btn-green">Attestation</a>
                                    @endif
                                </div>
                                <div class="col-md-4 line-spacer text-right">
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