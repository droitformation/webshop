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
        <li class="<?php echo (Request::is('admin/inscription') || Request::is('admin/inscription/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/inscription') }}">
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
        <li class="<?php echo (Request::is('admin/theme') || Request::is('admin/theme/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/theme') }}"><i class="fa fa-star-half-o"></i> <span>Thèmes</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/domain') || Request::is('admin/domain/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/domain') }}"><i class="fa fa-shield"></i> <span>Collections</span></a>
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

        <li class="nav-title">Sites</li>
        <li class="<?php echo (Request::is('admin/bloc') ? 'active' : '' ); ?>"><a href="{{ url('admin/bloc') }}"><i class="fa fa-reorder"></i> <span>Contenus</span></a></li>
        <li class="<?php echo (Request::is('admin/page') || Request::is('admin/page/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/page') }}"><i class="fa fa-file"></i> <span>Pages</span></a>
        </li>

        <li class="<?php echo (Request::is('admin/author') ? 'active' : '' ); ?>"><a href="{{ url('admin/author') }}"><i class="fa fa-user"></i> <span>Auteurs</span></a></li>

        <li class="<?php echo (Request::is('admin/arret/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/arret')  }}"><i class="fa fa-edit"></i> <span>Arrêts</span></a></li>
        <li class="<?php echo (Request::is('admin/analyse/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/analyse')  }}"><i class="fa fa-dot-circle-o"></i> <span>Analyses</span></a></li>

        <li class="<?php echo (Request::is('admin/categories/*') ? 'active' : '' ); ?>">
            <a href="javascript:;"><i class="fa fa-tasks"></i><span>Catégories</span></a>
            <ul class="acc-menu">
                @if(!$sites->isEmpty())
                    @foreach($sites as $site)
                        <li class="<?php echo (Request::is('admin/categories/'.$site->id) ? 'active' : '' ); ?>">
                            <a href="{{ url('admin/categories/'.$site->id)  }}">{{ $site->nom }}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </li>

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