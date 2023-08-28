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
        $realStates = auth('api')->user()->realStates()->paginate(10);

        return response()->json($realStates, 200);
    }

    public function show(int $id)
    {
        try {
            $realState = auth('api')->user()->realStates()->with('photos')->findOrFail($id);
            
            return response()->json($realState, 200);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth('api')->user()->id;
        $images = $request->file('images');
        
        try {
            $realState = $this->realState->create($data);
            
            if (isset($data['categories']) && count($data['categories']) > 0) {
                $realState->categories()->sync($data['categories']);
            }

            if ($images) {
                $imagesUploaded = [];

                foreach($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo' => $path,
                        'is_thumb' => false
                    ];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

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
        $images = $request->file('images');

        try {
            $realState = auth('api')->user()->realStates()->findOrFail($id);
            $realState->update($data);

            if (isset($data['categories']) && count($data['categories']) > 0) {
                $realState->categories()->sync($data['categories']);
            }

            if ($images) {
                $imagesUploaded = [];

                foreach($images as $image) {
                    $path = $image->store('images', 'public');
                    $imagesUploaded[] = [
                        'photo' => $path,
                        'is_thumb' => false
                    ];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

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
            $realState = auth('api')->user()->realStates()->findOrFail($id);
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
