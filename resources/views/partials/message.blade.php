@if( (isset($errors) && count($errors) > 0))

    <?php $class  = ($errors->has() ? 'warning' : Session::get('status')); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-{{ $class }}" alert-dismissible role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                @foreach($errors->all() as $message)
                    <p>{!! $message !!}</p>
                @endforeach

                {!! Session::get('message') !!}

                @if( $class != 'danger' && $class != 'success' &&  $class != 'warning' )
                    {{ $class }}
                @endif

            </div>
        </div>
    </div>

@endif
