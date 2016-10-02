@if(!$inscription->user_options->isEmpty())
    <h4>Options</h4>
    <ol>
        @foreach($inscription->user_options as $user_options)
            <li>{{ $user_options->option->title }}
                @if($user_options->option->type == 'choix')
                    <?php $user_options->load('option_groupe'); ?>
                    <p class="text-info">{{ isset($user_options->option_groupe) ?  $user_options->option_groupe->text : '' }}</p>
                @endif
            </li>
        @endforeach
    </ol>
@endif