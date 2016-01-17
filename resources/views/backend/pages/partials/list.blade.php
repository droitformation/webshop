@if(!$page->blocs->isEmpty())
    <?php $grouped = $page->blocs->groupBy('type'); ?>
    @foreach($grouped as $groupe => $blocs)
        <h5>{{ ucfirst($groupe) }}</h5>
        <ul class="list-group">
            @foreach($blocs as $bloc)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-9">
                            {!! !empty($bloc->name) ? $bloc->name : $bloc->content !!}
                        </div>
                        <div class="col-md-3">
                            <a href="#" data-id="{{ $bloc->id }}" data-page="{{ $bloc->page_id }}" class="btn btn-danger btn-xs pull-right delete-bloc"><i class="fa fa-times"></i></a>
                            <a href="#" data-id="{{ $bloc->id }}" class="btn btn-info btn-xs pull-right edit-bloc">éditer</a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endforeach
@endif