<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RealState;
use Ramsey\Uuid\Type\Integer;

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

    public function store(Request $request)
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
            return response()->json(['error' => $error->getMessage()], 401);
        }
    }

    public function update(Request $request, int $id)
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
            return response()->json(['error' => $error->getMessage()], 401);
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
            return response()->json(['error' => $error->getMessage()], 401);
        }
    }
}
