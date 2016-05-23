<?php $helper = new App\Droit\Helper\Helper(); ?>
@if(!$stocks->isEmpty())
    <table class="table table-bordered">
        <thead>
        <tr>
            <td><strong>Motif</strong></td>
            <td><strong>Opération</strong></td>
            <td><strong>Nombre</strong></td>
            <td><strong>État du stock</strong></td>
        </tr>
        </thead>
        <tbody>
        <?php $etat = 0; ?>
        @foreach($stocks as $stock)
            <?php $etat = $helper->calculSku($etat, $stock->amount, $stock->operator); ?>
            <tr>
                <td>{{ $stock->motif }}</td>
                <td>{!! $stock->operator == '+' ? '<span class="label label-success">+</span>' : '<span class="label label-danger">-</span>' !!}</td>
                <td>{{ $stock->amount }}</td>
                <td>{{ $etat }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif