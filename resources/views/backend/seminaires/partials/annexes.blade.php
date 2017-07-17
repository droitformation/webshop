@if($subject->annexe_path)
    @foreach($subject->annexe_path as $annexe)
        <p style="margin-top: 2px; margin-bottom: 2px;">
            <a class="btn btn-danger btn-sm annexes_delete_btn" data-id="{{ $subject->id }}" data-link="{{ $annexe['link'] }}">x</a>
            <a class="btn btn-sm btn-primary" target="_blank" href="{{ secure_asset($annexe['link']) }}"><i class="fa fa-file"></i> &nbsp;{{ $annexe['name'] }}</a>
        </p>
    @endforeach
@endif