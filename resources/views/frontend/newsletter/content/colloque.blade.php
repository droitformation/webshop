@if(isset($bloc->colloque))
    <div class="row">
        <div class="col-md-9">
            <div class="post">
                <div class="post-title">
                    <h3 class="title">{{ $bloc->colloque->titre }}</h3>
                </div><!--END POST-TITLE-->
                <div class="post-entry">
                    <p>{!! $bloc->colloque->soustitre !!}</p>
                    <p><strong>Organisé par: </strong><cite>{{ $bloc->colloque->organisateur }}</cite></p>
                    <p><a target="_blank"
                          style="padding: 5px 10px; text-decoration: none; background: {{ $campagne->newsletter->color }}; color: #fff; margin-top: 10px; display: inline-block;"
                          href="{{ url('pubdroit/colloque/'.$bloc->colloque->id) }}">Informations et inscription</a></p>
                </div>
            </div><!--END POST-->
        </div>
        <div class="col-md-3 text-center">
            <a target="_blank" href="{{ url('pubdroit/colloque/'.$bloc->colloque->id) }}">
                <img width="130" border="0" alt="{{ $bloc->colloque->titre }}" src="{{ secure_asset($bloc->colloque->frontend_illustration) }}" />
            </a>
        </div>
    </div>

@endif