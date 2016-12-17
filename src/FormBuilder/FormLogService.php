<?php

namespace Mcms\FrontEnd\FormBuilder;

use Event;
use Mcms\FrontEnd\Exceptions\InvalidProviderConfiguration;
use Mcms\FrontEnd\Models\FormBuilder;
use Mcms\FrontEnd\Models\FormLog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class FormLogService
 * @package Mcms\FrontEnd\FormBuilder
 */
class FormLogService
{
    /**
     * @var FormLog
     */
    public $model;


    /**
     * FormLogService constructor.
     */
    public function __construct()
    {
        $this->model = new FormLog();
    }


    /**
     * @param $id
     * @param array $item
     * @return FormLog|array
     */
    public function update($id, array $item)
    {
        $Item = $this->model->find($id);

        $Item->update($item);
        Event::fire('formLog.updated',$Item);

        return $Item;
    }

    /**
     * @param array $item
     * @return FormLog|array
     */
    public function store(array $item)
    {
        $Item = $this->model->create($item);
        Event::fire('formLog.created',$Item);

        return $Item;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function destroy($id)
    {
        $Item = $this->model->find($id);
        Event::fire('form.deleted',$Item);

        return $Item->delete();
    }

}