@if(!empty($inscription->documents))
    <div class="btn-group">
        @foreach($inscription->documents as $type => $annexe)
        <?php
        $file = config('documents.colloque.'.$type).$annexe['name'];
        echo '<a target="_blank" href="'.$file.'" class="btn btn-default btn-sm">'.strtoupper($type).'</a>';
        ?>
        @endforeach
    </div>
@endif