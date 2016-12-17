<?php

namespace Mcms\FrontEnd\Http\Controllers\Admin;

use Mcms\FrontEnd\FormBuilder\FormLogService;
use Mcms\FrontEnd\Models\Filters\FormLogFilters;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class FormLogController
 * @package Mcms\FrontEnd\Http\Controllers\Admin
 */
class FormLogController extends Controller
{
    /**
     * @var FormLogService
     */
    public $log;

    /**
     * FormLogController constructor.
     */
    public function __construct()
    {
        $this->log = new FormLogService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormLogFilters $filters)
    {
        $limit = ($filters->request->has('limit')) ? (int) $filters->request->input('limit') : 10;
        return response($this->log->model->with(['form'])
            ->filter($filters)
            ->paginate($limit));

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response($this->log->store($request->toArray()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response($this->log->model->with('form')->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response($this->log->update($id, $request->toArray()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(['success' => $this->log->destroy($id)]);
    }

    public function process(Request $request)
    {
        if ( ! $request->has('form')){
            //die... i don't know which form we're talking about
        }
        $Form = new FormLogService();
        $form = $Form->bySlug($request->form);
        if ( ! $form){
            //die, not a valid form
        }

        //process the data
        $Form->process($form->provider, $request, $form);

        return response(['success' => true]);
    }
}
