@extends('backend.layouts.master')
@section('content')
<?php $helper = new \App\Droit\Helper\Helper(); ?>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4><i class="fa fa-tasks"></i> &nbsp;Dernières inscriptions</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">

                    <table class="table normalTable" id="" style="margin-bottom: 0px;"><!-- Start inscriptions -->
                        <thead>
                        <tr>
                            <th class="col-sm-1">Action</th>
                            <th class="col-sm-3">Colloque</th>
                            <th class="col-sm-2">Déteteur</th>
                            <th class="col-sm-2">Email</th>
                            <th class="col-sm-2">Participant</th>
                            <th class="col-sm-1">No</th>
                            <th class="col-sm-1">Date</th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                            @if(!empty($inscriptions))
                                @foreach($inscriptions as $inscription)

                                    <?php $style = ($inscription->group_id > 0 ? 'class="isGoupe"' : ''); ?>

                                    <tr {!! $style !!}>
                                        <td><a class="btn btn-sky btn-xs btn-block" href="{{ url('admin/inscription/'.$inscription->id) }}">&Eacute;diter</a></td>
                                        <td>{{ $inscription->colloque->titre }}</td>
                                        <td>
                                            <?php
                                                echo ($inscription->group_id > 0 ? '<span class="label label-default">Groupe '.$inscription->group_id.'</span>' : '');
                                                echo ($inscription->adresse_facturation->company != '' ? '<p><strong>'.$inscription->adresse_facturation->company.'</strong><br/></p>' : '');
                                                echo '<p>'.$inscription->adresse_facturation->civilite_title.' '.$inscription->adresse_facturation->name.'</p>';
                                            ?>
                                        </td>
                                        <td>{{ $inscription->adresse_facturation->email }}</td>
                                        <td><?php echo ($inscription->group_id > 0 ? $inscription->participant->name :''); ?>
                                        </td>
                                        <td><strong>{{ $inscription->inscription_no }}</strong></td>
                                        <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table><!-- End inscriptions -->
                </div>
            </div>
        </div>

    </div>
</div>

@stop