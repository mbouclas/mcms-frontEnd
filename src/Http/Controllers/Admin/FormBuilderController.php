<?php

namespace Mcms\FrontEnd\Http\Controllers\Admin;

use Mcms\FrontEnd\FormBuilder\FormBuilderService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class FormBuilderController
 * @package Mcms\FrontEnd\Http\Controllers\Admin
 */
class FormBuilderController extends Controller
{
    /**
     * @var FormBuilderService
     */
    public $formBuilder;

    /**
     * FormBuilderController constructor.
     */
    public function __construct()
    {
        $this->formBuilder = new FormBuilderService();
    }

    public function schema(Request $request)
    {
        $schema = ( ! $request->has('schema')) ? $this->formBuilder->config['schema']['default'] : $request->schema;
        return response($this->formBuilder->schema($schema)->toArray());
    }

    public function template()
    {
        return \Config::get('formBuilder');
    }

    public function providers()
    {
        return response($this->formBuilder->providers()->load()->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->formBuilder->model->all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response($this->formBuilder->store($request->toArray()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response($this->formBuilder->model->find($id));
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
        return response($this->formBuilder->update($id, $request->toArray()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(['success' => $this->formBuilder->destroy($id)]);
    }

    public function process(Request $request)
    {
        if ( ! $request->has('form')){
            //die... i don't know which form we're talking about
        }
        $Form = new FormBuilderService();
        $form = $Form->bySlug($request->form);
        if ( ! $form){
            //die, not a valid form
        }

        //process the data
        $Form->process($form->provider, $request, $form);

        return response(['success' => true]);
    }
}
