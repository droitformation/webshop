<div class="row">
    <div class="col-md-12">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Publications Droit</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="{{ Request::is('shop') ? 'active' : '' }}"><a href="/">Shop</a></li>
                    <li class="{{ Request::is('colloque') || Request::is('colloque/*') ? 'active' : '' }}"><a href="{{ url('colloque') }}">Colloques</a></li>
                </ul>

                @include('frontend.partials.usernav')

            </div>
        </nav>
    </div>
</div>