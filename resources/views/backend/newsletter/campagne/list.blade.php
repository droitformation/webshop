@if(!$campagnes->isEmpty())
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th class="col-md-2">Sujet</th>
            <th class="col-md-3">Auteurs</th>
            <th class="col-md-1">Status</th>
            <th class="col-md-2"></th>
            <th class="col-md-1"></th>
            <th class="col-md-2"></th>
            <th class="col-md-1"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($campagnes as $campagne)
            <tr>
                <td><strong><a href="{{ url('admin/campagne/'.$campagne->id.'/edit') }}">{{ $campagne->sujet }}</a></strong></td>
                <td>{{ $campagne->auteurs }}</td>
                <td>
                    @if($campagne->status == 'brouillon')
                        <span class="badge badge-default">Brouillon</span>
                    @else
                        <span class="badge badge-success">Envoyé</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-inverse btn-sm" href="{{ url('admin/campagne/'.$campagne->id) }}">Composer</a>
                        @if($campagne->status == 'envoyé')
                            <a class="btn btn-primary btn-sm" href="{{ url('admin/statistics/'.$campagne->id) }}">Stats</a>
                            <a href="javascript:;" class="btn btn-default btn-sm sendEmailNewsletter" data-campagne="{{ $campagne->id }}">Envoyer par email</a>
                        @endif
                    </div>
                </td>
                <td>
                    @if($campagne->status == 'brouillon')
                        <form action="{{ url('admin/campagne/send') }}" id="sendCampagneForm" method="POST">
                            {!! csrf_field() !!}
                            <input name="id" value="{{ $campagne->id }}" type="hidden">
                            <a href="javascript:;" data-campagne="{{ $campagne->id }}" class="btn btn-sm btn-warning btn-block" id="bootbox-demo-3">
                                <i class="fa fa-exclamation"></i> &nbsp;Envoyer la campagne
                            </a>
                        </form>
                    @else
                        Envoyé le {{ $campagne->updated_at->formatLocalized('%d %b %Y') }} à {{ $campagne->updated_at->toTimeString() }}
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#sendToList">Envoyer à une liste</button>
                    @include('backend.newsletter.template.partials.send', ['campagne' => $campagne])
                </td>
                <td class="text-right">
                    <form action="{{ url('admin/campagne/'.$campagne->id) }}" method="POST">
                        <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                        <button data-action="campagne {{ $campagne->sujet }}" class="btn btn-danger btn-xs deleteAction"><i class="fa fa-remove"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>Aucune newsletter en cours</p>
@endif
