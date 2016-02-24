@if(isset($user->inscriptions) && !$user->inscriptions->isEmpty())

    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-5">Colloque</th>
                <th class="col-md-1">N°</th>
                <th class="col-md-2">Date</th>
                <th class="col-md-2">Envoyé le</th>
                <th class="text-right col-md-1">Montant</th>
                <th class="text-right col-md-1">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user->inscriptions as $inscription)
                <?php $inscription->load('colloque','rappels'); ?>
                <tr class="mainRow">
                    <td>
                        <a class="collapse_anchor" data-toggle="collapse" href="#inscription_no_{{ $inscription->id }}">
                            <i class="fa fa-arrow-circle-right"></i>
                            {{ $inscription->colloque->titre }}
                        </a>
                    </td>
                    <td><strong>{{ $inscription->inscription_no }}</strong></td>
                    <td>{{ $inscription->created_at->formatLocalized('%d %b %Y') }}</td>
                    <td>
                        @if($inscription->send_at)
                            <span class="fa fa-paper-plane"></span>
                            {{ $inscription->send_at->formatLocalized('%d %b %Y') }}
                        @endif
                    </td>
                    <td class="text-right">{{ $inscription->price_cents }} CHF</td>
                    <td class="text-right"><span class="label label-{{ $inscription->status_name['color'] }}">{{ $inscription->status_name['status'] }}</span></td>
                </tr>
                <tr>
                    <td colspan="6" class="nopadding">

                        <div class="collapse customCollapse" id="inscription_no_{{ $inscription->id }}">

                            <div class="inscription_wrapper">
                                <div class="row">
                                    <div class="col-md-2">
                                        <h4>Payement</h4>
                                        @if($inscription->payed_at)
                                            <h1 class="label label-success" style="font-size: 90%;">Payé le {{ $inscription->payed_at->format('d/m/Y') }}</h1>
                                        @else
                                            <h1 class="label label-warning" style="font-size: 90%;">En attente</h1>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <h4>Documents</h4>
                                        @if(!empty($inscription->documents))
                                            <div class="btn-group">
                                                @foreach($inscription->documents as $type => $annexe)
                                                    <?php
                                                    $file = config('documents.colloque.'.$type).$annexe['name'];
                                                    echo '<a target="_blank" href="'.$file.'" class="btn btn-default">'.strtoupper($type).'</a>';
                                                    ?>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <h4>Options</h4>
                                        @if(!$inscription->user_options->isEmpty())
                                            <ol>
                                                @foreach($inscription->user_options as $user_options)
                                                    <li>{{ $user_options->option->title }}
                                                        @if($user_options->option->type == 'choix')
                                                            <?php $user_options->load('option_groupe'); ?>
                                                            <p class="text-info">{{ $user_options->option_groupe->text }}</p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        @if(!$inscription->payed_at)
                                            <hr/>
                                            <form action="{{ url('admin/inscription/rappel') }}" method="POST">{!! csrf_field() !!}
                                                <input type="hidden" name="id" value="{{ $inscription->id }}">
                                                <button class="btn btn-inverse btn-sm"><i class="fa fa-paperclip"></i> &nbsp;Générer un rappel</button>
                                            </form>

                                            @if(!$inscription->rappels->isEmpty())
                                                <ol class="list-group">
                                                    @foreach($inscription->rappels as $rappel)
                                                        <li class="list-group-item">Rappel {{ $rappel->created_at->format('d/m/Y') }}</li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        @if(!empty($inscription->colloque->annexe))
                                            <hr/>
                                            <a href="{{ url('admin/inscription/generate/'.$inscription->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> &nbsp;Regénérer les documents</a>
                                            <a href="{{ url('/') }}" class="btn btn-sm btn-success"><i class="fa fa-trophy"></i> &nbsp;Attestation</a>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <hr/>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editInscription_{{ $inscription->id }}">
                                            <i class="fa fa-star"></i> &nbsp;&Eacute;diter l'inscription
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#sendInscription_{{ $inscription->id }}">
                                            <i class="fa fa-send-o"></i> &nbsp;Envoyer l'inscription
                                        </button>
                                    </div>
                                </div>

                                @include('backend.users.partials.send', ['inscription' => $inscription])
                                @include('backend.users.partials.edit', ['inscription' => $inscription])
                            </div>
                        </div>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <p>Encore aucune inscription</p>
@endif