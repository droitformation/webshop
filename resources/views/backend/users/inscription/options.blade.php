@if(!$inscription->user_options->isEmpty())
    <h4>Options</h4>
    <ol>
        @foreach($inscription->user_options as $user_options)
            <li>{{ $user_options->option->title }}
                @if($user_options->option->type == 'choix')
                    <?php $user_options->load('option_groupe'); ?>
                    <p class="text-info">{{ isset($user_options->option_groupe) ?  $user_options->option_groupe->text : '' }}</p>
                @endif
                @if($user_options->option->type == 'text')
                    <p class="text-info">{{ $user_options->reponse }}</p>
                @endif
            </li>
        @endforeach
    </ol>
@endif