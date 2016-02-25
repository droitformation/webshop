@if(isset($user->inscription_groupes) && !$user->inscription_groupes->isEmpty())
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-2">Groupe</th>
                <th class="col-md-5">Colloque</th>
                <th class="col-md-2">Date</th>
                <th class="col-md-2">Envoyé le</th>
                <th class="text-right col-md-1">Statut</th>
            </tr>
        </thead>
        <tbody>

            <!-- Group start -->
            @foreach($user->inscription_groupes as $group)

                <?php $group->load('inscriptions','colloque'); ?>

                <tr class="mainRow">
                    <td><strong>Groupe {{ $group->id }}</strong></td>
                    <td>
                        <a class="collapse_anchor" data-toggle="collapse" href="#inscription_no_{{ $group->id }}">
                            <i class="fa fa-arrow-circle-right"></i>{{ $group->colloque->titre }}
                        </a>
                    </td>
                    <td>{{ $group->inscriptions->first()->created_at->formatLocalized('%d %b %Y') }}</td>
                    <td>
                        @if($group->inscriptions->first()->send_at)
                            <span class="fa fa-paper-plane"></span> &nbsp;{{ $group->inscriptions->first()->send_at->formatLocalized('%d %b %Y') }}
                        @endif
                    </td>
                    <td class="text-right">
                        <span class="label label-{{ $group->inscriptions->first()->status_name['color'] }}">
                            {{ $group->inscriptions->first()->status_name['status'] }}
                        </span>
                    </td>
                </tr>

                <!-- Inscription details -->
                <tr>
                    <td colspan="6" class="nopadding">
                        <div class="collapse customCollapse" id="inscription_no_{{ $group->id }}">
                            <div class="inscription_wrapper">

                                <!-- Inscription dependences -->
                                <table class="table-inscriptions">
                                    <thead>
                                        <tr class="row">
                                            <th class="col-md-2"><h4>N°</h4></th>
                                            <th class="col-md-2"><h4>Payement</h4></th>
                                            <th class="col-md-4"><h4>Documents</h4></th>
                                            <th class="col-md-4"><h4>Options</h4></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Inscription loop -->
                                    @foreach($group->inscriptions as $inscription)

                                        <?php $inscription->load('colloque','rappels'); ?>

                                        <tr class="row">
                                            <td class="col-md-2"><p><strong>{{ $inscription->inscription_no }}</strong></p></td>
                                            <td class="col-md-2">
                                                @if($inscription->payed_at)
                                                    <h1 class="label label-success" style="font-size: 90%;">Payé le {{ $inscription->payed_at->format('d/m/Y') }}</h1>
                                                @else
                                                    <h1 class="label label-warning" style="font-size: 90%;">En attente</h1>
                                                @endif
                                            </td>
                                            <td class="col-md-4">@include('backend.users.inscription.documents')</td>
                                            <td class="col-md-4">@include('backend.users.inscription.options')</td>
                                        </tr>

                                    @endforeach
                                    <!-- END Inscription loop -->
                                    </tbody>
                                </table>
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
@else
    <p>Encore aucune inscription</p>
@endif