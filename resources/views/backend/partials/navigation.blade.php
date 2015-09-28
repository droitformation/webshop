<!-- BEGIN SIDEBAR -->
<nav id="page-leftbar" role="navigation">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="acc-menu" id="sidebar">
        <!-- Recherche globale -->
       <!-- @include('backend.partials.search')-->

        <li class="<?php echo (Request::is('admin') ? 'active' : '' ); ?>"><a href="{{ url('admin') }}">
                <i class="fa fa-home"></i> <span>Accueil</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/config') || Request::is('admin/config/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/config') }}">
                <i class="fa fa-cog"></i> <span>Configurations</span>
            </a>
        </li>
        <li class="divider"></li>
        <li class="<?php echo (Request::is('admin/colloque') || Request::is('admin/colloque/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/colloque') }}">
                <i class="fa fa-institution"></i> <span>Colloques</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/inscription') || Request::is('admin/inscription/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/inscription') }}">
                <i class="fa fa-table"></i> <span>Inscription</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/coupon') || Request::is('admin/coupon/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/coupon') }}">
                <i class="fa fa-star"></i> <span>Coupons</span>
            </a>
        </li>
    </ul>
    <!-- END SIDEBAR MENU -->
</nav>