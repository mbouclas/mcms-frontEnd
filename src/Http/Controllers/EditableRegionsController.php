<?php

namespace Mcms\FrontEnd\Http\Controllers;

use Mcms\FrontEnd\Services\EditableRegions;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use ItemConnector;

class EditableRegionsController extends BaseController
{
    protected $regions;

    public function __construct(EditableRegions $regions)
    {
        $this->regions = $regions;
    }

    public function index()
    {
        return  [
            'regions' => $this->regions
                ->filter()
//                ->process()
                ->get(false, true),
            'connectors' => ItemConnector::connectors()
        ];
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $found = $this->regions
            ->filter($id)
            ->update($id, $data);
        return $found;
    }
}