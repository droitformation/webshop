<div class="col-md-3 sidebar">
    <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <div class="absolute-wrapper"> </div>
        <!-- Menu -->
        <div class="side-menu">
            <!-- Main Menu -->
            <div class="side-menu-container">
                <div class="list-group">
                    <a href="{{ url('profil') }}" class="list-group-item {{ Request::is('profil') ? 'active' : '' }}">
                        <h5 class="list-group-item-heading">Mes données</h5>
                        <p class="list-group-item-text">compte et adresse</p>
                    </a>
                    <a href="{{ url('profil/orders') }}" class="list-group-item {{ Request::is('profil/orders') ? 'active' : '' }}">
                        <h5 class="list-group-item-heading">Mes achats</h5>
                        <p class="list-group-item-text">Commandes en cours/archives</p>
                    </a>
                    <a href="{{ url('profil/colloques') }}" class="list-group-item {{ Request::is('profil/colloques') ? 'active' : '' }}">
                        <h5 class="list-group-item-heading">Mes inscriptions</h5>
                        <p class="list-group-item-text">Colloques et événements</p>
                    </a>
                </div>
            </div><!-- /.navbar-collapse -->
        </div>
    </div>
</div>