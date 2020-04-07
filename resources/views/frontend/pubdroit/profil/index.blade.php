@extends('frontend.pubdroit.layouts.master')
@section('content')

<section class="row">
    <div class="col-md-12">

        <div class="heading-bar">
            <h2>Profil</h2>
            <span class="h-line"></span>
        </div>

        <section class="row">
            <aside class="col-md-3">
                <ul class="nav nav-list">
                    <li class="{{ Request::is('pubdroit/profil') ? 'active' : '' }}"><a href="{{ url('pubdroit/profil') }}"><i class="fa fa-home"></i> &nbsp;Données</a></li>
                    <li class="{{ Request::is('pubdroit/book') ? 'active' : '' }}"><a href="{{ url('pubdroit/profil/book') }}"><i class="fa fa-book"></i> &nbsp;book</a></li>
                    <li class="{{ Request::is('pubdroit/profil/orders') ? 'active' : '' }}"><a href="{{ url('pubdroit/profil/orders') }}"><i class="fa fa-shopping-cart"></i> &nbsp;Commandes</a></li>
                    <li class="{{ Request::is('pubdroit/profil/colloques') || Request::is('pubdroit/profil/inscription/*') ? 'active' : '' }}"><a href="{{ url('pubdroit/profil/colloques') }}"><i class="fa fa-calendar"></i> &nbsp;Inscriptions</a></li>
                    <li class="{{ Request::is('pubdroit/profil/abos') ? 'active' : '' }}"><a href="{{ url('pubdroit/profil/abos') }}"><i class="fa fa-book"></i> &nbsp;Abonnements</a></li>
                    <li class="{{ Request::is('pubdroit/profil/subscriptions') ? 'active' : '' }}"><a href="{{ url('pubdroit/profil/subscriptions') }}"><i class="fa fa-paper-plane"></i> &nbsp;Abonnements aux newsletter</a></li>
                </ul>
            </aside>
            <div class="col-md-9">

                <!-- Contenu -->
                @yield('profil')
                <!-- Fin contenu -->

            </div>
        </section>

    </div>
</section>
	
@stop
