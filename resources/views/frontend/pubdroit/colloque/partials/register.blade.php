<form role="form" id="colloque-inscription" method="POST" action="{{ url('pubdroit/registration') }}">
    {!! csrf_field() !!}

    <h3>Inscription</h3>
    @include('frontend.pubdroit.colloque.partials.wizard')

</form>
