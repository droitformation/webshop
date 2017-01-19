<!-- BEGIN SIDEBAR -->
<nav id="page-leftbar" role="navigation">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="acc-menu" id="sidebar">
        <li class="<?php echo (Request::is('team') ? 'active' : '' ); ?>"><a href="{{ url('admin') }}">
            <i class="fa fa-home"></i> <span>Accueil</span></a>
        </li>
        <li class="divider"></li>
        <li class="<?php echo (Request::is('team/orders') ? 'active' : '' ); ?>">
            <a href="{{ url('team/orders') }}"><i class="fa fa-shopping-cart"></i> <span>Commandes</span></a>
        </li>
        <li class="<?php echo (Request::is('team/colloque') ? 'active' : '' ); ?>">
            <a href="{{ url('team/colloque') }}"><i class="fa fa-flag"></i> <span>Colloques</span></a>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</nav>