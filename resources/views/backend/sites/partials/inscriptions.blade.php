<div class="panel panel-info">
    <div class="panel-body">
        <h4 class="text-info"><i class="fa fa-table"></i> &nbsp;Dernières inscriptions</h4>
        <table class="table">
            <thead>
            <tr>
                <th class="col-sm-1">Action</th>
                <th class="col-sm-3">Déteteur</th>
                <th class="col-sm-2">No</th>
                <th class="col-sm-2">Date</th>
            </tr>
            </thead>
            <tbody class="selects">
                @if(!empty($inscriptions))
                    @foreach($inscriptions as $inscription)
                        <tr {!! ($inscription->group_id > 0 ? 'class="isGoupe"' : '') !!}>
                            <td><a class="btn btn-info btn-sm" href="{{ url('admin/inscription/'.$inscription->id) }}"><i class="fa fa-edit"></i></a></td>
                            <td>
                                <?php
                                    echo ($inscription->group_id > 0 ? '<span class="label label-default">Groupe '.$inscription->group_id.'</span>' : '');
                                    if($inscription->inscrit)
                                    {
                                        echo ($inscription->inscrit->company != '' ? '<p><strong>'.$inscription->adresse_facturation->company.'</strong></p>' : '');
                                        echo '<p>'.($inscription->group_id > 0 ? $inscription->participant->name : $inscription->inscrit->name).'</p>';
                                    }
                                ?>
                            </td>
                            <td><strong>{{ $inscription->inscription_no }}</strong></td>
                            <td>{{ $inscription->created_at->formatLocalized('%d %B %Y') }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
