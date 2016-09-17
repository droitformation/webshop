@if(!empty($inscription->documents))
    <div class="btn-group">
        @foreach($inscription->documents as $type => $annexe)
            <a target="_blank" href="{{ asset($annexe['link']) }}{{ '?'.rand(0,1000) }}" class="btn btn-default btn-sm">
                <i class="fa fa-file"></i> &nbsp;{{ strtoupper($type) }}
            </a>
        @endforeach
    </div>
@endif

@if($inscription->doc_attestation)
    <div class="btn-group">
        <a target="_blank" href="{{ asset($inscription->doc_attestation) }}{{ '?'.rand(0,1000) }}" class="btn btn-default btn-sm"><i class="fa fa-file"></i> &nbsp;Attestation</a>
    </div>
@endif

@if(!$inscription->rappels->isEmpty())
    <div class="list_rappels">
        <h4>Rappels</h4>
        <ol>
            @foreach($inscription->rappels as $rappel)
                <li>
                    <form action="{{ url('admin/inscription/rappel/'.$rappel->id) }}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                        <a target="_blank" href="{{ asset($rappel->doc_rappel) }}"><i class="fa fa-file"></i> &nbsp; Rappel {{ $rappel->created_at->format('d/m/Y') }}</a> &nbsp;
                        <button data-what="Supprimer" data-action="le rappel" class="btn btn-danger btn-sm deleteAction"><i class="fa fa-times"></i></button>
                    </form>
                </li>
            @endforeach
        </ol>
    </div>
@endif