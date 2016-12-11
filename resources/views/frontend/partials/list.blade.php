<div class="widget clear">
    <h3 class="title">Liste</h3>
    @if(!$lists->isEmpty())
        <div class="sidebar-list">
            <ul class="menu">
                @foreach($lists as $list_id => $list)
                    <li>
                        <a class="{{ (isset($var) && $var == $list_id) ? 'active' : '' }}" href="{{ url($site->slug.'/page/'.$page->slug.'/'.$list_id) }}">
                            {{ str_replace('Droit matrimonial - ','',$list) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
  </div>