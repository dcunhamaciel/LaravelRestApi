<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RealState;

class RealStateSearchController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $realStates = $this->realState->paginate(10);

        return response()->json($realStates, 200);
    }

    public function show(int $id)
    {
    }
}
