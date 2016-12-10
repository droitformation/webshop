
@if($bloc->type == 'soutien')
    <h3 class="title">Avec le soutien de</h3>
@else
    @if(!empty($bloc->title))
        <h3 class="title">{{ $bloc->title }}</h3>
    @endif
@endif

<div class="bloc-content">

    @if(!empty($bloc->url) && !empty($bloc->image))
        <a style="float: left; margin-right: 10px;" href="{{ $bloc->url }}" target="_blank">
            <img style="max-width: 160px" src="{{ asset('/files/uploads/'.$bloc->image) }}" alt="">
        </a>
    @elseif(!empty($bloc->image))
        <img style="max-width: 100px; float: left; margin-right: 10px;" src="{{ asset('/files/uploads/'.$bloc->image) }}" alt="">
    @endif

    @if(!empty($bloc->content))
        {!! $bloc->content !!}
    @endif
    <div class="clearfix"></div>
</div>