<!-- BEGIN SIDEBAR -->
<nav id="page-leftbar" role="navigation">
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="acc-menu" id="sidebar">
        <!-- Recherche globale -->
        @include('backend.partials.search')

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

        <li class="<?php echo (Request::is('admin/dejeuner') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/dejeuner') }}">
                <i class="fa fa-calendar"></i> <span>Déjeuners académiques</span>
            </a>
        </li>

        <li class="<?php echo (Request::is('admin/reminder') || Request::is('admin/reminder/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/reminder') }}">
                <i class="fa fa-bolt"></i> <span>Rappels (livres,colloques)</span>
            </a>
        </li>

        <li class="nav-title">Utiliateurs et adresses</li>

        <li class="<?php echo (Request::is('admin/user') || Request::is('admin/user/*') ? 'active' : '' ); ?>">
            <a href="javascript:;"><i class="fa fa-users"></i><span>Utilisateurs</span></a>
            <ul class="acc-menu">
                <li><a href="{{ url('admin/users') }}"><span>Liste des utilisateurs</span></a></li>
                <li><a href="{{ url('admin/user/create') }}"><span>Ajouter utilisateur (admin)</span></a></li>
            </ul>
        </li>
        <li class="<?php echo (Request::is('admin/adresse') || Request::is('admin/adresse/*') ? 'active' : '' ); ?>">
            <a href="javascript:;"><i class="fa fa-street-view"></i><span>Adresses</span></a>
            <ul class="acc-menu">
                <li><a href="{{ url('admin/adresses') }}"><span>Liste des adresses</span></a></li>
                <li><a href="{{ url('admin/adresse/create') }}"><span>Ajouter une adresse</span></a></li>
                <li><a href="{{ url('admin/deletedadresses') }}"><span>Recherche Adresse/Compte</span></a></li>
            </ul>
        </li>

        <li class="<?php echo (Request::is('admin/export/view') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/export/view') }}">
                <i class="fa fa-cloud-download"></i>&nbsp;<span>Export ou recherche</span>
            </a>
        </li>
        <li class="<?php echo (Request::is('admin/email') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/email') }}"><i class="fa fa-envelope"></i>&nbsp;<span>Email log</span></a>
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
        <li class="<?php echo (Request::is('admin/compte') || Request::is('admin/compte/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/compte') }}"><i class="fa fa-calculator"></i> <span>Comptes</span></a>
        </li>

        <li class="{{ (Request::is('admin/sondage') || Request::is('admin/sondage/*') || Request::is('admin/avis') || Request::is('admin/avis/*')) ? 'active' : '' }}">
            <a href="javascript:;"><i class="fa fa-question-circle"></i><span>Sondages</span></a>
            <ul class="acc-menu">
                <li class="{{ Request::is('admin/sondage/*') ? 'active' : '' }}"><a href="{{ url('admin/sondage')  }}">Liste des sondages</a></li>
                <li class="{{ Request::is('admin/avis/*') ? 'active' : '' }}"><a href="{{ url('admin/avis')  }}">Catalogue de questions</a></li>
            </ul>
        </li>

        <li class="<?php echo (Request::is('admin/rabais') || Request::is('admin/rabais/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/rabais') }}">
                <i class="fa fa-arrow-circle-down"></i> <span>Coupon prix colloque</span>
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
        <li class="<?php echo (Request::is('admin/resume') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/resume') }}"><i class="fa fa-send"></i> <span>Résumé envois</span></a>
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
        <li class="<?php echo (Request::is('admin/shopauthor') || Request::is('admin/shopauthor/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/shopauthor') }}"><i class="fa fa-user"></i> <span>Auteurs ouvrages</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/theme') || Request::is('admin/theme/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/theme') }}"><i class="fa fa-star-half-o"></i> <span>Collections</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/domain') || Request::is('admin/domain/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/domain') }}"><i class="fa fa-shield"></i> <span>Thèmes</span></a>
        </li>
        <li class="<?php echo (Request::is('admin/attribut') || Request::is('admin/attribut/*') ? 'active' : '' ); ?>">
            <a href="{{ url('admin/attribut') }}"><i class="fa fa-flag"></i> <span>Attributs</span></a>
        </li>

        <li class="nav-title">Contenus</li>
        <li class="<?php echo (Request::is('admin/author') || Request::is('admin/author/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/author') }}"><i class="fa fa-user"></i> <span>Auteurs</span></a></li>
        <li class="<?php echo (Request::is('admin/specialisation') || Request::is('admin/specialisation/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/specialisation') }}"><i class="fa fa-tag"></i> <span>Spécialisations</span></a></li>
        <li class="<?php echo (Request::is('admin/member') || Request::is('admin/member/*') ? 'active' : '' ); ?>"><a href="{{ url('admin/member') }}"><i class="fa fa-tags"></i> <span>Membres</span></a></li>

        <li class="nav-title">Envois</li>

        <li class="<?php echo (Request::is('build/newsletter/*')
            || Request::is('build/campagne/*')
            || Request::is('build/subscriber/*')
            || Request::is('build/import/*')
            || Request::is('build/liste/*')
            || Request::is('build/purge/*')
            ? 'active' : '' ); ?>">
            <a href="javascript:;"><i class="fa fa-envelope"></i><span>Newsletters</span></a>
            <ul class="acc-menu">
                <li class="<?php echo (Request::is('build/newsletter/*') ? 'active' : '' ); ?>"><a href="{{ url('build/newsletter')  }}">Liste des campagnes</a></li>
                <li class="<?php echo (Request::is('build/subscriber/*') ? 'active' : '' ); ?>"><a href="{{ url('build/subscriber')  }}">Abonnés</a></li>
                <li class="<?php echo (Request::is('build/import') ? 'active' : '' ); ?>"><a href="{{ url('build/import')  }}">Importer une liste</a></li>
                <li class="<?php echo (Request::is('build/liste') ? 'active' : '' ); ?>"><a href="{{ url('build/liste')  }}">Liste hors campagnes</a></li>
                <li class="<?php echo (Request::is('build/purge') ? 'active' : '' ); ?>"><a href="{{ url('build/purge')  }}">Nettoyage</a></li>
            </ul>
        </li>

    </ul>
    <!-- END SIDEBAR MENU -->
</nav>