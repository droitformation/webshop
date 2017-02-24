<div class="event-post col-md-6">
    <div class="post-img">
        <a href="{{ url('pubdroit/colloque/'.$colloque->id) }}">
            <?php $illustraton = $colloque->illustration ? $colloque->illustration->path : 'illu.png'; ?>
            <img src="{{ secure_asset('files/colloques/illustration/'.$illustraton) }}" alt=""/>
        </a>
    </div>
    <div class="post-det">
        <h3><a href="{{ url('pubdroit/colloque/'.$colloque->id) }}"><strong>{{ $colloque->titre }}</strong></a></h3>
        <p>{{ $colloque->soustitre }}</p>
        <p><i class="fa fa-calendar"></i> &nbsp;{{ $colloque->event_date }}</p>
        <p><strong>Lieu: </strong>
            {{ $colloque->location ? $colloque->location->name : '' }}, {!! $colloque->location ? $colloque->location->adresse : ''  !!}</p>
        {!! $colloque->remarque !!}
    </div>
    <div class="clearfix"></div>
</div>
