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
                <li class="<?php echo (Request::is('admin/config/abo') ? 'active' : '' ); ?>"><a href="{{ url('admin/config/abo')  }}">Abonnements</a></li>
                <li class="<?php echo (Request::is('admin/config/colloque') ? 'active' : '' ); ?>"><a href="{{ url('admin/config/colloque')  }}">Colloque</a></li>
            </ul>
        </li>
        <li class="<?php echo (Request::is('admin/reminder') || Request::is('admin/reminder/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/reminder') }}">
                <i class="fa fa-bolt"></i> <span>Rappels</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/location') || Request::is('admin/location/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/location') }}">
                <i class="fa fa-map-marker"></i> <span>Lieux</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/organisateur') || Request::is('admin/organisateur/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/organisateur') }}">
                <i class="fa fa-certificate"></i> <span>Organisateur</span>
            </a>
        </li>
        <li class="nav-title">Comptes</li>
        <li class="<?php echo (Request::is('admin/search/form') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/search/form') }}">
                <i class="fa fa-users"></i> <span>Recherche</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/export/view') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/export/view') }}">
                <i class="fa fa-cloud-download"></i> &nbsp;<span>Export</span>
            </a>
        </li>
        <li class="nav-title">Evenements</li>
        <li class="<?php echo (Request::is('admin/colloque') || Request::is('admin/colloque/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/colloque') }}">
                <i class="fa fa-flag"></i> <span>Colloques</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/inscription/create') || Request::is('admin/inscription/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/inscription/create') }}">
                <i class="fa fa-table"></i> <span>Inscription</span>
            </a>
        </li>
        <li class="nav-title">Shop</li>
        <li class="<?php echo (Request::is('admin/abo') || Request::is('admin/abo/*') || Request::is('admin/abonnements/*') || Request::is('admin/abonnement/*') || Request::is('admin/facture/*') || Request::is('admin/factures/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/abo') }}"><i class="fa fa-bookmark"></i> <span>Abonnements</span></a>
        </li>
        <li class="divider"></li>
        <li class="<?php echo (Request::is('admin/product') || Request::is('admin/product/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/products') }}"><i class="fa fa-book"></i> <span>Livres</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/orders') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/orders') }}"><i class="fa fa-shopping-cart"></i> <span>Commandes</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/order/create') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/order/create') }}"><i class="fa fa-plus-circle"></i> <span>Nouvelle commande</span></a>
        </li>
        <li class="divider"></li>
        <li class="<?php echo (Request::is('admin/coupon') || Request::is('admin/coupon/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/coupon') }}"><i class="fa fa-star"></i> <span>Coupons</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/shipping') || Request::is('admin/shipping/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/shipping') }}"><i class="fa fa-truck"></i> <span>Frais de port</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/theme') || Request::is('admin/theme/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/theme') }}"><i class="fa fa-star-half-o"></i> <span>Thèmes</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/domain') || Request::is('admin/domain/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/domain') }}"><i class="fa fa-shield"></i> <span>Collections</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/attribut') || Request::is('admin/attribut/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/attribut') }}"><i class="fa fa-flag"></i> <span>Attributs</span></a>
        </li>

        <li class="nav-title">Contenus</li>
        <li class="<?php echo (Request::is('admin/author') || Request::is('admin/author/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/author') }}"><i class="fa fa-user"></i> <span>Auteurs</span></a></li>
        <li class="<?php echo (Request::is('admin/specialisation') || Request::is('admin/specialisation/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/specialisation') }}"><i class="fa fa-tag"></i> <span>Spécialisations</span></a></li>
        <li class="<?php echo (Request::is('admin/member') || Request::is('admin/member/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/member') }}"><i class="fa fa-tags"></i> <span>Membres</span></a></li>

        <li class="nav-title">Envois</li>

        <li class="<?php echo (Request::is('admin/newsletter/*') || Request::is('admin/campagne/*') || Request::is('admin/subscriber/*') ? 'active' : '' ); ?>">
            <a href="javascript:;"><i class="fa fa-envelope"></i><span>Newsletters</span></a>
            <ul class="acc-menu">
                <li class="<?php echo (Request::is('admin/newsletter/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/newsletter')  }}">Liste des newsletters</a></li>
                <li class="<?php echo (Request::is('admin/subscriber/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/subscriber')  }}">Abonnées</a></li>
                <li class="<?php echo (Request::is('admin/import') ? 'active' : '' ); ?>"><a href="{{ url('admin/import')  }}">Importer une liste</a></li>
            </ul>
        </li>
        <li><a href="{{ url('admin/user/create') }}"><i class="fa fa-user"></i><span>Ajouter utilisateur admin</span></a></li>
    </ul>
    <!-- END SIDEBAR MENU -->
</nav>