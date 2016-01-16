@if(!$page->blocs->isEmpty())
    <?php $grouped = $page->blocs->groupBy('type'); ?>
    @foreach($grouped as $groupe => $blocs)
        <h5>{{ ucfirst($groupe) }}</h5>
        <ul class="list-group">
            @foreach($blocs as $bloc)
                <li class="list-group-item">{!! $bloc->name !!}
                    <a href="#" data-what="supprimer" data-action="{{ $groupe }}" class="btn btn-danger btn-xs pull-right deleteAction"><i class="fa fa-times"></i></a>
                    <a href="#" class="btn btn-info btn-xs pull-right">éditer</a>
                </li>
            @endforeach
        </ul>
    @endforeach
@endif