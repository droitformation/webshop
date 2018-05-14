@if(!$colloque->slides->isEmpty())
    <button type="button" class="btn btn-primary btn-sm btn-download" data-toggle="modal" data-target="#download_{{ $colloque->id }}">
        Documents à télécharger
    </button>

    <!-- Modal -->
    <div class="modal fade" id="download_{{ $colloque->id }}" tabindex="-1" role="dialog" aria-labelledby="download_{{ $colloque->id }}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="download_{{ $colloque->id }}">Documents à télécharger</h4>
                </div>
                <div class="modal-body">
                    @foreach($colloque->slides as $slide)
                        <p>
                            <a target="_blank" href="{{ $slide->getUrl() }}" class="btn btn-primary">
                                <i class="fa fa-download"></i> &nbsp;{{ $slide->getCustomProperty('title', '') }}
                            </a>
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif