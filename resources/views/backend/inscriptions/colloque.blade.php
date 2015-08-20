@extends('backend.layouts.master')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>Inscriptions</h2>
            <p>&nbsp;</p>
        </div>
    </div>

    <table class="table table-list">
        <thead>
            <tr>
                <th class="numeroInscriptionSorter">N°</th>
                <th>Date</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th class="{sorter: false}">ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
                echo '<pre>';
                print_r($inscriptions);
                echo '</pre>';
            ?>
        </tbody>
    </table>

@stop