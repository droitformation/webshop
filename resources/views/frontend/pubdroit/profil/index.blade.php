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
                    <li class="{{ Request::is('profil') ? 'active' : '' }}"><a href="{{ url('profil') }}"><i class="fa fa-home"></i> &nbsp;Données</a></li>
                    <li class="{{ Request::is('profil/orders') ? 'active' : '' }}"><a href="{{ url('profil/orders') }}"><i class="fa fa-shopping-cart"></i> &nbsp;Commandes</a></li>
                    <li class="{{ Request::is('profil/colloques') || Request::is('profil/inscription/*') ? 'active' : '' }}"><a href="{{ url('profil/colloques') }}"><i class="fa fa-calendar"></i> &nbsp;Inscriptions</a></li>
                </ul>
            </aside>
            <div class="col-md-9">
                @yield('profil')
            </div>
        </section>

    </div>
</section>
	
@stop
