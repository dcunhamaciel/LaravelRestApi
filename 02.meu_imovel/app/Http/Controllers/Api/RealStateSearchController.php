<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RealState;
use App\Repository\RealStateRepository;
use App\Api\Messages\ApiMessages;

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
        $repository->setLocation($request->all(['state_id', 'city_id']));

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
        try {
            $realState = $this->realState
                ->with('address')
                ->with('photos')
                ->findOrFail($id);

            return response()->json($realState, 200);
        } catch (\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }
}
