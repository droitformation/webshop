<h3 class="title">{{ $page->menu_title }}<i class="pull-right fa fa-arrow-circle-right"></i></h3>

<div class="collapse {{ Request::is($site->slug.'/page/'.$page->slug) || Request::is($site->slug.'/page/'.$page->slug.'/*') ? 'in' : '' }}" id="collapse{{ $page->template }}">
    @if(!$lists->isEmpty())
        <ul class="menu">
            @foreach($lists as $list_id => $list)
                <li>
                    <a class="{{ (isset($var) && $var == $list_id) ? 'active' : '' }}" href="{{ url($site->slug.'/page/'.$page->slug.'/'.$list_id) }}">
                        {{ str_replace('Droit matrimonial - ','',$list) }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>