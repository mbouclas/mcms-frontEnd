<?php

namespace Mcms\FrontEnd\FormBuilder;

use Config;
use Mcms\Core\Services\Menu\AdminInterfaceConnector;
use Mcms\FrontEnd\Models\Filters\FormFilters;
use Mcms\FrontEnd\Models\FormBuilder;
use Illuminate\Http\Request;


class FormsInterfaceMenuConnector extends AdminInterfaceConnector
{
    /**
     * @var string
     */
    protected $moduleName = 'Forms';
    /**
     * @var array
     */
    protected $sections = [];
    /**
     * @var FormBuilder
     */
    protected $model;

    protected $filters;

    protected $type = 'generic';

    protected $order = 100;

    public function __construct()
    {
        $this->model = new FormBuilder();
        $this->sections = $this->getSections();

        parent::__construct($this->model);

        return $this;
    }

    /**
     * Setup the sections needed for the admin interface to render the menu selection
     *
     * @return array
     */
    private function getSections(){
        //extract it to a config file maybe
        return [
            [
                'name' => 'Items',
                'filterService' => 'Mcms\FrontEnd\FormBuilder\FormsInterfaceMenuConnector',
                'filterMethod' => 'filterItems',
                'settings' => [
                    'preload' => true,
                    'filter' => true
                ],
                'filters' => [
                    ['key'=>'id', 'label'=> '#ID', 'default' => true],
                    ['key'=>'title', 'label'=> 'Title'],
                ],
                'titleField' => 'title',
                'slug_pattern' => null
            ],
        ];
    }

    public function filterItems(Request $request, $section){
        $results = $this->model->filter(new FormFilters($request))->get();

        if (count($results) == 0){
            return ['data' => []];
        }

        //now formulate the results
        $toReturn = [];

        foreach ($results as $result){

            $toReturn[] = [
                'item_id' => $result->id,
                'title' => $result->title,
                'module' => $this->moduleName,
                'model' => get_class($result),
                'section' => $section
            ];
        }

        $results = $results->toArray();
        $results['data'] = $toReturn;


        return ['data' => $toReturn];
    }
}