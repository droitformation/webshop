<?php

function getGroup($group_id,$option_id,$colloque){
    $option = $colloque->options->find($option_id);
    $option = $option->load('groupe');
    $groupe = $option->groupe->find($group_id);

    return [
        'title' => $option->title,
        'text'  => $groupe->text
    ];
}

function getOption($option_id,$colloque){

    if(is_array($option_id)){
        return collect($option_id)->mapWithKeys(function ($item,$id) use ($colloque) {
            $option = $colloque->options->find($id);
            return ['title' => $option->title, 'text' => $item];
        })->reject(function ($value, $key) {
            return empty($value);
        })->toArray();
    }

    $option = $colloque->options->find($option_id);

    return ['title' => $option->title];
}

function getOccurrences($occurrence_id,$colloque){
    $occurrence = $colloque->occurrences->find($occurrence_id);
    return ['title' => $occurrence->title];
}

