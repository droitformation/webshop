<div class="panel panel-midnightblue">
    <div class="panel-body">
        <h3>Inscriptions invalides</h3>
        <p>Il manque probablement l'adresse ou l'utilisateur, ou l'adresse n'est pas de type contact</p>
        <ul class="list-group">
            @foreach($invalid as $register)
                <?php
                    $error = new App\Droit\Inscription\Entities\Invalid($register);
                    $error->trashedUser()->getAdresse();
                ?>
                <li class="list-group-item">
                    <p><strong>No:</strong> {{ $register->inscription_no }}</p>
                    @if($error->invalid)
                        @foreach($error->invalid as $type => $message)
                            <p>
                                <cite>{{ $message['message'] }}</cite>
                                @if($error->restoreUrl($type))
                                    : <a href="{{ $error->restoreUrl($type) }}">Restaurer</a>
                                @endif
                            </p>
                        @endforeach
                    @endif

                    @if($error->adresse)
                        @foreach($error->adresse as $line)
                            <p>{{ $line }}</p>
                        @endforeach
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>