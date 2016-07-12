<!-- Rappels -->
@foreach($rappels as $rappel)
    <div class="row">
        <div class="col-md-5">
            @if($rappel->abo_rappel)
               <a class="btn btn-xs btn-default" target="_blank" href="{{ asset($rappel->abo_rappel) }}"><i class="fa fa-file"></i> &nbsp;Rappel pdf</a>
            @endif
        </div>
        <div class="col-md-5">
            <p><span class="label label-warning"><i class="fa fa-star"></i></span>&nbsp;&nbsp;<strong>Rappel le {!! $rappel->created_at->formatLocalized('%d %B %Y') !!}</strong></p>
        </div>
        <div class="col-md-2">
            @include('backend.abonnements.partials.delete', ['payement' => $rappel, 'type' => 'rappel'])
        </div>
    </div>
@endforeach
<!-- End Rappels -->