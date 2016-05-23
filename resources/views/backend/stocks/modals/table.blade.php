<?php $helper = new App\Droit\Helper\Helper(); ?>

@if(!$stocks->isEmpty())
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <td width="10%"><strong>Date</strong></td>
            <td width="55%"><strong>Motif</strong></td>
            <td width="5%"><strong></strong></td>
            <td width="15%"><strong>Nbr.</strong></td>
            <td width="15%"><strong>&Eacute;tat du stock</strong></td>
        </tr>
        </thead>
        <tbody>
        <?php $etat = 0; ?>
        @foreach($stocks as $stock)
            <?php $etat = $helper->calculSku($etat, $stock->amount, $stock->operator); ?>
            <tr>
                <td>{{ $stock->created_at->format('d/m/y') }}</td>
                <td>{{ $stock->motif }}</td>
                <td>{!! $stock->operator == '+' ? '<span class="label label-success">+</span>' : '<span class="label label-danger">-</span>' !!}</td>
                <td>{{ $stock->amount }}</td>
                <td>{{ $etat }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif