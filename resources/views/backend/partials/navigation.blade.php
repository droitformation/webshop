<!-- BEGIN SIDEBAR -->
<nav id="page-leftbar" role="navigation">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="acc-menu" id="sidebar">
        <!-- Recherche globale -->
       <!-- @include('backend.partials.search')-->

        <li class="divider"></li>
        <li class="<?php echo (Request::is('admin') ? 'active' : '' ); ?>"><a href="{{ url('admin') }}">
             <i class="fa fa-home"></i> <span>Accueil</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/colloque') || Request::is('admin/colloque/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/colloque') }}">
                <i class="fa fa-edit"></i> <span>Colloques</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/inscription') || Request::is('admin/inscription/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/inscription') }}">
                <i class="fa fa-edit"></i> <span>Inscription</span>
            </a>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</nav>