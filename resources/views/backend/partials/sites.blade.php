@if(isset($sites) && !$sites->isEmpty())
    @foreach($sites as $site)
        <li class="dropdown demodrop sitedrop">
            <a href="#" class="dropdown-toggle tooltips color-{{ $site->slug }}" data-toggle="dropdown">{{ $site->nom }}</a>
            <ul class="dropdown-menu arrow dropdown-menu-form site-dropdown">
                <li>
                    <a class="shortcut-tiles tiles-sky" href="{{ url('admin/menus/'.$site->id) }}">
                        <div class="tiles-body tiles-body-menu">
                            <i class="fa fa-list"></i><p class="pull-right">Menus</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="shortcut-tiles tiles-orange" href="{{ url('admin/pages/'.$site->id) }}">
                        <div class="tiles-body tiles-body-menu">
                            <i class="fa fa-file"></i><p class="pull-right">Pages</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="shortcut-tiles tiles-primary" href="{{ url('admin/blocs/'.$site->id) }}">
                        <div class="tiles-body tiles-body-menu">
                            <i class="fa fa-list"></i><p class="pull-right">Bloc de contenus</p>
                        </div>
                    </a>
                </li>
                @if($site->id == 1)
                    <li>
                        <a class="shortcut-tiles tiles-inverse" href="{{ url('admin/orders') }}">
                            <div class="tiles-body tiles-body-menu">
                                <i class="fa fa-shopping-cart"></i><p class="pull-right">Commandes</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="shortcut-tiles tiles-indigo" href="{{ url('admin/inscriptions') }}">
                            <div class="tiles-body tiles-body-menu">
                                <i class="fa fa-calendar"></i><p class="pull-right">Inscriptions</p>
                            </div>
                        </a>
                    </li>
                @endif

                @if($site->id == 2)
                    <li>
                        <a class="shortcut-tiles tiles-magenta" href="{{ url('admin/calculette/taux') }}">
                            <div class="tiles-body tiles-body-menu">
                                <i class="fa fa-percent"></i><p class="pull-right">Calculette Taux</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a class="shortcut-tiles tiles-green" href="{{ url('admin/calculette/ipc') }}">
                            <div class="tiles-body tiles-body-menu">
                                <i class="fa fa-sort-numeric-asc"></i><p class="pull-right">Calculette IPC</p>
                            </div>
                        </a>
                    </li>
                @endif

                @if(!$site->arrets->isEmpty())
                    <li>
                        <a class="shortcut-tiles tiles-success" href="{{ url('admin/arrets/'.$site->id) }}">
                            <div class="tiles-body tiles-body-menu">
                                <i class="fa fa-edit"></i><p class="pull-right">Arrêts</p>
                            </div>
                        </a>
                    </li>
                @endif

                @if(!$site->analyses->isEmpty())
                    <li>
                        <a class="shortcut-tiles tiles-brown" href="{{ url('admin/analyses/'.$site->id) }}">
                            <div class="tiles-body tiles-body-menu">
                                <i class="fa fa-dot-circle-o"></i><p class="pull-right">Analyses</p>
                            </div>
                        </a>
                    </li>
                @endif

            </ul>
        </li>
    @endforeach
@endif