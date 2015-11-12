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
            <a href="javascript:;"><i class="fa fa-cog"></i><span>Configurations</span></a>
            <ul class="acc-menu">
                <li class="<?php echo (Request::is('admin/config/shop') ? 'active' : '' ); ?>"><a href="{{ url('admin/config/shop')  }}">Shop</a></li>
                <li class="<?php echo (Request::is('admin/config/colloque') ? 'active' : '' ); ?>"><a href="{{ url('admin/config/colloque')  }}">Colloque</a></li>
            </ul>
        </li>
        <li class="nav-title">Comptes</li>
        <li class="<?php echo (Request::is('admin/search/form') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/search/form') }}">
                <i class="fa fa-users"></i> <span>Recherche</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/export/user') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/export/user') }}">
                <i class="fa fa-cloud-download"></i> &nbsp;<span>Export</span>
            </a>
        </li>
        <li class="nav-title">Evenements</li>
        <li class="<?php echo (Request::is('admin/colloque') || Request::is('admin/colloque/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/colloque') }}">
                <i class="fa fa-flag"></i> <span>Colloques</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/inscription') || Request::is('admin/inscription/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/inscription') }}">
                <i class="fa fa-table"></i> <span>Inscription</span>
            </a>
        </li>
        <li class="nav-title">Shop</li>
        <li class="<?php echo (Request::is('admin/product') || Request::is('admin/product/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/product') }}"><i class="fa fa-book"></i> <span>Livres</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/coupon') || Request::is('admin/coupon/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/coupon') }}"><i class="fa fa-star"></i> <span>Coupons</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/shipping') || Request::is('admin/shipping/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/shipping') }}"><i class="fa fa-truck"></i> <span>Frais de port</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/orders') || Request::is('admin/order/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/orders') }}"><i class="fa fa-shopping-cart"></i> <span>Commandes</span></a>
        </li>
        <li class="nav-title">Sites</li>
        <li class="<?php echo (Request::is('admin/contenu') ? 'active' : '' ); ?>"><a href="{{ url('admin/contenu') }}"><i class="fa fa-reorder"></i> <span>Contenus</span></a></li>
        <li class="<?php echo (Request::is('admin/author') ? 'active' : '' ); ?>"><a href="{{ url('admin/author') }}"><i class="fa fa-user"></i> <span>Auteurs</span></a></li>
        <li class="<?php echo (Request::is('admin/arret/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/arret')  }}"><i class="fa fa-edit"></i> <span>Arrêts</span></a></li>
        <li class="<?php echo (Request::is('admin/analyse/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/analyse')  }}"><i class="fa fa-dot-circle-o"></i> <span>Analyses</span></a></li>
        <li class="<?php echo (Request::is('admin/categorie/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/categorie')  }}"><i class="fa fa-tasks"></i> <span>Categories</span></a></li>

        <li class="nav-title">Envois</li>

        <li class="<?php echo (Request::is('admin/newsletter/*') || Request::is('admin/campagne/*') || Request::is('admin/subscriber/*') ? 'active' : '' ); ?>">
            <a href="javascript:;"><i class="fa fa-envelope"></i><span>Newsletters</span></a>
            <ul class="acc-menu">
                <li class="<?php echo (Request::is('admin/newsletter/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/newsletter')  }}">Liste des newsletters</a></li>
                <li class="<?php echo (Request::is('admin/subscriber/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/subscriber')  }}">Abonnées</a></li>
            </ul>
        </li>

    </ul>
    <!-- END SIDEBAR MENU -->
</nav>