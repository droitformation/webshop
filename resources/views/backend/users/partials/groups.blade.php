<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-6">Colloque</th>
            <th class="col-md-4">Montant</th>
        </tr>
    </thead>
    <tbody>
        <!-- Group start -->
        @foreach($inscriptions_groupe as $group)
            <tr class="mainRow">
                <td>
                    <a class="collapse_anchor" data-toggle="collapse" href="#inscription_no_{{ $group->id }}">
                        <i class="fa fa-arrow-circle-right"></i>{{ $group->colloque->titre }}
                    </a>
                </td>
                <td>{{ $group->price_cents }} CHF</td>
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
                                @if(!$group->inscriptions->isEmpty())
                                    <!-- Inscription loop -->
                                    @foreach($group->inscriptions as $inscription)

                                        <?php $inscription->load('colloque','rappels','participant'); ?>

                                        @include('backend.users.partials.single-group', ['inscription' => $inscription])
                                    @endforeach
                                    <!-- END Inscription loop -->
                                @endif
                                </tbody>
                            </table>

                            <!-- Inscription updates buttons -->

                            @include('backend.users.partials.group-menu')
                            <!-- END Inscription updates buttons -->

                            <!-- Modals add to and edit group -->
                            @include('backend.inscriptions.modals.add', ['group' => $group, 'colloque' => $group->colloque])
                            @include('backend.inscriptions.modals.change', ['group' => $group])
                            @include('backend.users.modals.sendgroup', ['group' => $group]) <!-- Modal send inscription -->
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
