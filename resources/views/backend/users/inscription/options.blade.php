@if(!$inscription->user_options->isEmpty())
    <h4>Options</h4>
    <?php setlocale(LC_ALL, 'fr_FR.UTF-8'); ?>
    <ol>
        @foreach($inscription->user_options as $user_options)
            @if(isset($user_options->option))
                <li>{{ $user_options->option->title }}
                    @if($user_options->option->type == 'choix')
                        <?php $user_options->load('option_groupe'); ?>
                        <p class="text-info">{{ isset($user_options->option_groupe) ?  $user_options->option_groupe->text : '' }}</p>
                    @endif
                    @if($user_options->option->type == 'text')
                        <p class="text-info">{{ $user_options->reponse }}</p>
                    @endif
                </li>
            @else
                <?php  $option = $user_options->option()->withTrashed()->get(); ?>
                <p class="text-muted">
                    Attention, option supprimé {{ !$option->isEmpty() ? $option->first()->title : $user_options->option_id }}
                </p>
            @endif
        @endforeach
    </ol>
@endif