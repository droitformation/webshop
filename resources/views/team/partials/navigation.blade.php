<!-- BEGIN SIDEBAR -->
<nav id="page-leftbar" role="navigation">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="acc-menu" id="sidebar">
        <li class="<?php echo (Request::is('admin') ? 'active' : '' ); ?>"><a href="{{ url('admin') }}">
            <i class="fa fa-home"></i> <span>Accueil</span></a>
        </li>
        <li class="divider"></li>
        <li class="<?php echo (Request::is('admin/orders') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/orders') }}"><i class="fa fa-shopping-cart"></i> <span>Commandes</span></a>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</nav>