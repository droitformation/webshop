
@if($bloc->type == 'soutien')
    <h5>Avec le soutien de</h5>
@else
    @if(!empty($bloc->title))
        <h5>{{ $bloc->title }}</h5>
    @endif
@endif

<div class="bloc-content">

    @if(!empty($bloc->url) && !empty($bloc->image))
        <a href="{{ $bloc->url }}" target="_blank">
            <img style="max-width: 100px" src="{{ asset('/files/uploads/'.$bloc->image) }}" alt="">
        </a>
    @elseif(!empty($bloc->image))
        <p><img style="max-width: 100px" src="{{ asset('/files/uploads/'.$bloc->image) }}" alt=""></p>
    @endif

    @if(!empty($bloc->content))
        <h5>{!! $bloc->content !!}}</h5>
    @endif

</div>