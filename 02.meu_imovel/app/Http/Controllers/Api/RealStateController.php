<?php

namespace App\Http\Controllers\Api;

use App\Api\Messages\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $realState = $this->realState->paginate(10);

        return response()->json($realState, 200);
    }

    public function show(int $id)
    {
        try {
            $realState = $this->realState->findOrFail($id);
            
            return response()->json($realState, 200);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();

        try {
            $realState = $this->realState->create($data);

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel cadastrado com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update(RealStateRequest $request, int $id)
    {
        $data = $request->all();

        try {
            $realState = $this->realState->findOrFail($id);
            $realState->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel atualizado com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy(int $id)
    {
        try {
            $realState = $this->realState->findOrFail($id);
            $realState->delete($id);

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel removido com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
