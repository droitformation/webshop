<!-- BEGIN SIDEBAR -->
<nav id="page-leftbar" role="navigation">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="acc-menu" id="sidebar">
        <li class="<?php echo (Request::is('access') ? 'active' : '' ); ?>"><a href="{{ url('access') }}">
            <i class="fa fa-home"></i> <span>Accueil</span></a>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</nav>