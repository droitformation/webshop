<div class="row">
    <div class="col-md-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">SHOP</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="/">Shop</a></li>
                    <li class="{{ Request::is('colloque') || Request::is('colloque/*') ? 'active' : '' }}"><a href="/colloque">Colloques</a></li>
                </ul>
                @if (!Auth::check())
                    <div class="btn-group pull-right">
                        <a href="{{ url('auth/login')}}" class="btn btn-info navbar-btn">{{ trans('message.login') }}</a>
                        <a href="{{ url('auth/register')}}" class="btn btn-warning navbar-btn">{{ trans('message.register') }}</a>
                    </div>
                @endif
                @if (Auth::check())
                    <p class="navbar-text navbar-right">Bonjour {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} &nbsp;</p>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle btn-default" data-toggle="dropdown" role="button" aria-expanded="false">Mon compte <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('profil') }}">Mes données</a></li>
                                <li><a href="{{ url('profil') }}">Mes achats</a></li>
                                <li><a href="{{ url('profil') }}">Mes inscriptions</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('auth/logout') }}">Déconnexion</a></li>
                            </ul>
                        </li>
                    </ul>
                @endif
            </div>
        </nav>
    </div>
</div>