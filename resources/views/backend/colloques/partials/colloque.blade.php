@foreach($colloques as $chunk)
    <div class="row">
        @foreach($chunk as $colloque)
            <div class="col-md-3">
                <div class="panel panel-{{ $color }}">
                    <div class="panel-body panel-colloque">
                        <?php $illustraton = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
                        <div class="media">
                            <div class="media-left">
                                <span class="no-colloque">{{ $colloque->id }}</span>
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
                        <div class="btn-group">
                            <a class="btn btn-sky btn-sm" href="{{ url('admin/colloque/'.$colloque->id) }}">&Eacute;diter</a>
                            <a class="btn btn-success btn-sm" href="{{ url('admin/inscription/colloque/'.$colloque->id) }}">Inscriptions</a>
                        </div>
                        <form action="{{ url('admin/colloque/'.$colloque->id) }}" method="POST" class="pull-right">
                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                            <button data-action="{{ $colloque->titre }}" class="btn btn-danger btn-sm deleteAction">x</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach