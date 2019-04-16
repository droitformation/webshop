<?php
namespace App\Droit\Generate\Export;

class ExportInscription{

    protected $columns = [];
    protected $sort = null;
    protected $dispatch = null;

    public $options = null;
    public $groupes = null;
    public $checkboxes = null;
    public $sorted = [];

    public function __construct()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    public function setDispatch($dispatch)
    {
        $this->dispatch = $dispatch;

        return $this;
    }

    /*
     * column names
     * if we want to sort
     * */
    public function export($inscriptions, $colloque = null, $occurences = null)
    {
        $colloque->load('options','groupes');

        // Get options and grouped options
        $this->options    = isset($colloque->options) ? $colloque->options->where('type', 'choix')->pluck('title', 'id')->toArray() : [];
        $this->groupes    = $colloque->groupes->pluck('text', 'id')->toArray();
        $this->checkboxes = isset($colloque->options) ? $colloque->options->where('type', 'checkbox')->pluck('title', 'id')->toArray() : [];

        // Group by occurrences if any else just group on 0 to have a loop
        $grouped = $inscriptions->groupBy(function ($item, $key) use ($occurences){
            if($this->dispatch) {
                if(!empty(array_filter($occurences))) {
                    return $item->occurrences->whereIn('id',$occurences)->pluck('title')->all();
                }

                return $item->occurrences->pluck('title')->all();
            }
            return 0;
        });

        \Excel::create('Export inscriptions', function ($excel) use ($grouped) {
            $excel->sheet('Export', function ($sheet) use ($grouped) {
                $sheet->setOrientation('landscape');

                // start grouped loop and test if we need to display the name of the occureence
                foreach ($grouped as $group => $inscriptions) {

                    // Prepare the inscriptions with infos
                    $converted = $this->prepareInscription($inscriptions);

                    if($this->sort)
                    {
                        if($this->sort == 'choice' && !empty($this->options)) {
                            $this->makeTitle($sheet,$group);
                            $this->sortChoices($sheet,$converted);
                        }

                        if($this->sort == 'checkbox' && !empty($this->checkboxes)) {
                            $this->makeTitle($sheet,$group);
                            $this->sortCheckboxes($sheet,$converted);
                        }
                    }
                    else {
                        $this->makeHeader($sheet,$group);
                        $sheet->rows($this->unsetFilters($converted));
                    }

                } // end grouped loop
            });

        })->export('xls');

    }

    protected function makeHeader($sheet, $group = '')
    {
        $header = ['Présent', 'Numéro', 'Prix', 'Status', 'Date', 'Participant'] + $this->columns;

        if($this->dispatch) {
            $sheet->appendRow(['']);
            $sheet->appendRow([$group]);
            $sheet->row($sheet->getHighestRow(), function ($row) {
                $row->setFontSize(15)->setFontWeight('bold');
            });
            $sheet->appendRow(['']);
        }

        $sheet->appendRow($header);
        $sheet->row($sheet->getHighestRow(), function ($row) {
            $row->setFontSize(13)->setFontWeight('bold');
        });
    }

    protected function makeTitle($sheet,$title)
    {
        if(!empty($title)) {
            $sheet->appendRow([$title]);
            $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(16)->setFontColor('#009cff');});
            $sheet->mergeCells('A'.$sheet->getHighestRow().':H'.$sheet->getHighestRow())->appendRow(['']);
        }
    }

    protected function sortChoices($sheet,$converted){

        $names['option_title'] = 'Choix';

        foreach ($converted as $option_id => $options)
        {
            $option_name = isset($this->options[$option_id]) ? $this->options[$option_id] : 'Aucun choix';

            $sheet->appendRow([$option_name]);

            $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(16)->setFontColor('#595959');});
            $sheet->mergeCells('A'.$sheet->getHighestRow().':H'.$sheet->getHighestRow())->appendRow(['']);

            foreach ($options as $group_id => $data)
            {
                $sheet->appendRow([isset($this->groupes[$group_id]) ? '- '.$this->groupes[$group_id] : 'aucun']);
                $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(14)->setFontColor('#003e65'); })->appendRow(['']);

                $this->makeHeader($sheet);

                $data = $this->unsetFilters($data);

                $sheet->rows($data)->appendRow(['']);
            }
        }

        $sheet->appendRow(['']);
    }

    public function sortCheckboxes($sheet,$converted)
    {
        $names['option_title'] = 'Choix';

        foreach ($converted as $option_id => $data)
        {
            $data = $this->unsetFilters($data);

            $sheet->appendRow([isset($this->checkboxes[$option_id]) ? $this->checkboxes[$option_id] : 'Pas d\'option choisi']);
            $sheet->row($sheet->getHighestRow(), function ($row) {$row->setFontWeight('bold')->setFontSize(16)->setFontColor('#595959');});
            $sheet->mergeCells('A'.$sheet->getHighestRow().':H'.$sheet->getHighestRow())->appendRow(['']);

            $this->makeHeader($sheet);
            $sheet->rows($data)->appendRow(['']);
        }

        $sheet->appendRow(['']);
    }

    public function formatInscription($inscription)
    {
        $data = [];
        $user = $inscription->inscrit;

        $data['Present']     = $inscription->present ? 'Oui' : '';
        $data['Numéro']      = $inscription->inscription_no;
        $data['Prix']        = $inscription->price_cents;
        $data['Status']      = $inscription->status_name['status'];
        $data['Date']        = $inscription->created_at->format('m/d/Y');
        $data['Participant'] = ($inscription->group_id > 0 ? $inscription->participant->name : '');

        // Adresse columns
        if($user && !$user->adresses->isEmpty())
        {
            $names = collect($this->columns);
            $data += $names->map(function ($item, $key) use ($user) {
                return $user->adresses->first()->$key;
            })->toArray();
        }
        else{
            $data += ['Le compte a été supprimé!'];
        }

        // String with the options
        if (!$inscription->user_options->isEmpty()) {
            $data['all_options'] = $this->userOptionsHtml($inscription->user_options);

            $data['filter_choices'] = $inscription->user_options->filter(function ($option, $key) {
                return in_array($option->option_id, array_keys($this->options)) ? true : false;
            })->toArray();

            $data['filter_checkboxes'] = $inscription->user_options->filter(function ($option, $key) {
                return in_array($option->option_id, array_keys($this->checkboxes)) ? true : false;
            })->toArray();
        }
        else{
            $data['filter_checkboxes'] = [];
            $data['filter_choices'] = [];
        }

        return $data;
    }

    public function prepareInscription($inscriptions)
    {
        if(!$inscriptions->isEmpty()) {

            $converted = $inscriptions->map(function ($inscription, $key){
                return $this->formatInscription($inscription);
            });

            // Do we need to sort, Sort by choix options
            if($this->sort) {
                foreach($converted as $inscription){
                    // Sort each person in each options
                    $depth  = $this->sort == 'choice' ? 2 : 1;
                    $filter = $this->sort == 'choice' ? $inscription['filter_choices'] : $inscription['filter_checkboxes'];

                    $this->sortByOption($filter, $inscription, $depth);
                }
            }
            else{
                $this->sorted = $converted;
            }
        }

        return $this->sorted;
    }

    public function sortByOption($filter, $data, $depth = 2)
    {
        if(empty($filter)){
            $this->sorted[0][] = $data;
        }

        // Sort each person in each options
        array_walk($filter, function (&$value,$key) use ($data,$depth) {
            if($depth == 1){
                $this->sorted[$value['option_id']][] = $data;
            }
            if($depth == 2){
                $this->sorted[$value['option_id']][$value['groupe_id']][] = $data;
            }
        });
    }

    public function unsetFilters($data)
    {
        return collect($data)->map(function ($data, $key) {
            if(isset($data['filter_choices'])){
                unset($data['filter_choices']);
            }
            if(isset($data['filter_checkboxes'])){
                unset($data['filter_checkboxes']);
            }
            return $data;
        })->toArray();
    }

    public function userOptionsHtml($user_options)
    {
        return $user_options->map(function ($group, $key)
        {
            if(!isset($group->option)){
                $option = $group->option()->withTrashed()->get();
                $option = !$option->isEmpty() ? $option->first() : null;

                return $option ? 'Ancienne option: '.$option->title.($group->groupe_id ? ':' : '').($group->groupe_id ? $group->option_groupe->text : '') : '';
            }

            return $group->option->title.($group->groupe_id ? ':' : '').($group->groupe_id && isset($group->option_groupe) ? $group->option_groupe->text : '');

        })->implode(';');
    }
}