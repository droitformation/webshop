
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
                            <form action="{{ url('admin/pagecontent/'.$bloc->id) }}" method="POST" class="text-right">
                                <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                <a data-toggle="collapse" href="#pagecontent_{{ $bloc->id }}" class="btn btn-info btn-xs">éditer</a>
                                <button data-what="supprimer" data-action="bloc: {{!empty($bloc->name) ? $bloc->name : $bloc->content }}" class="btn btn-danger btn-xs deleteAction">x</button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endforeach
