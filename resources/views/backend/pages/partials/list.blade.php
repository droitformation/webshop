@if(!$page->contents->isEmpty())
    <?php $grouped = $page->contents->groupBy('type'); ?>
    <?php $icons = ['text' => 'align-justify','autorite' => 'building', 'loi' => 'gavel', 'lien' => 'link', 'faq' => 'question-circle']; ?>
    @foreach($grouped as $groupe => $blocs)
        <h5><i class="fa fa-{{ $icons[$groupe] }}"></i> &nbsp;{{ ucfirst($groupe) }}</h5>
        <ul class="list-group sortcontent">
            @foreach($blocs->sortBy('rang') as $bloc)
                <li class="list-group-item" id="page_rang_{{ $bloc->id }}">
                    <div class="row">
                        <div class="col-md-9">
                            <i id="msgEditOk" class="fa fa-check"></i>
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