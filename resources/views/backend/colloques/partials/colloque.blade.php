@foreach($colloques as $chunk)
    <div class="row">
        @foreach($chunk as $colloque)
            <div class="col-md-3">
                <div class="panel panel-{{ $color }}">
                    <div class="panel-body panel-colloque">
                        <?php $illustraton = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                        <span class="no-colloque">{{ $colloque->id }}</span>
                        <div class="media">
                            <div class="media-left">
                                <a href="{{ url('admin/colloque/'.$colloque->id) }}">
                                    <img class="media-object" width="50px" src="{{ asset('files/colloques/illustration/'.$illustraton) }}" />
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="{{ url('admin/colloque/'.$colloque->id) }}">{{ $colloque->titre }}</a></h4>
                                <p>{{ $colloque->sujet }}</p>
                                <p><i class="fa fa-calendar"></i> &nbsp;{{ $colloque->event_date }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <form action="{{ url('admin/colloque/'.$colloque->id) }}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                            <div class="btn-group btn-group-colloque">
                                <a class="btn btn-sky btn-sm" href="{{ url('admin/colloque/'.$colloque->id) }}">&Eacute;diter</a>
                                <a class="btn btn-success btn-sm" href="{{ url('admin/inscription/colloque/'.$colloque->id) }}">Inscriptions &nbsp;
                                <span class="badge badge-success">
                                    {{ $colloque->inscriptions->count() }}
                                </span>
                                </a>
                                <a class="btn btn-warning btn-sm" href="{{ url('admin/inscription/rappels/'.$colloque->id) }}">Rappels</a>
                                <button data-action="{{ $colloque->titre }}" class="btn btn-danger btn-sm deleteAction">x</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach