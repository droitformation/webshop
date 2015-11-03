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
                <?php $inscription->load('colloque'); ?>
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

                            <div class="row inscription_wrapper">
                                <div class="col-md-2">
                                    <h4>Payement</h4>
                                    @if($inscription->payed_at)
                                        <h1 class="label label-success" style="font-size: 90%;">Payé le {{ $inscription->payed_at->format('d/m/Y') }}</h1>
                                    @else
                                        <h1 class="label label-warning" style="font-size: 90%;">En attente</h1>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <h4>Documents</h4>
                                    @if(!empty($inscription->documents))
                                        @foreach($inscription->documents as $type => $annexe)
                                            <?php
                                            $file = config('documents.colloque.'.$type).$annexe['name'];
                                            echo '<a target="_blank" href="'.$file.'" class="btn btn-sm btn-block btn-default">'.strtoupper($type).'</a>';
                                            ?>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <h4>Actions</h4>
                                </div>
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