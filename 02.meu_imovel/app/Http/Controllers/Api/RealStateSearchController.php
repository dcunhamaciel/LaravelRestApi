<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RealState;
use App\Repository\RealStateRepository;

class RealStateSearchController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index(Request $request)
    {
        $repository = new RealStateRepository($this->realState);

        if ($request->has('conditions')) {
            $repository->selectConditions($request->get('conditions'));
        }
        
        if ($request->has('fields')) {
            $repository->selectFilter($request->get('fields'));
        }

        $realStates = $repository->getResult()->paginate(10);

        return response()->json($realStates, 200);
    }

    public function show(int $id)
    {
    }
}
