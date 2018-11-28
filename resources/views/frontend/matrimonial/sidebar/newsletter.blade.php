@if(!$newsletters->isEmpty())
    <div class="widget clear">
        <h3 class="title">Inscription à la newsletter</h3>
        @foreach($newsletters as $newsletter)
            <p><a data-fancybox data-type="iframe"
                  data-src="{{ url('site/subscribe/'.$newsletter->site->id) }}"
                  class="btn btn-default btn-block"
                  href="javascript:;">
                    Je m'inscrit!
                </a>
            </p>
          {{--  @include('frontend.newsletter.partials.subscribe', ['newsletter' => $newsletter, 'return_path' => 'matrimonial'])--}}
        @endforeach

        <p style="margin-top: 8px;">Je souhaite me <a data-fancybox data-type="iframe"
                                                      data-src="{{ url('site/unsubscribe/'.$newsletter->site->id) }}"
                                                      href="javascript:;">désinscrire</a></p>
    </div>
@endif