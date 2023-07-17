<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Api\Messages\ApiMessages;
use App\Models\RealStatePhoto;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId)
    {
        try {
            $photo = $this->realStatePhoto
                ->where('real_state_id', $realStateId)
                ->where('is_thumb', true);

            if ($photo->count()) {
                $photo->first()->update(['is_thumb' => false]);
            }

            $photo = $this->realStatePhoto->find($photoId);
            $photo->update(['is_thumb' => true]);

            return response()->json([
                'data' => [
                    'msg' => 'Thumb atualizada com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

    public function remove($photoId)
    {
        try {
            $photo = $this->realStatePhoto->find($photoId);
            
            if (!$photo) { 
                $message = new ApiMessages('Foto não localizada!');

                return response()->json($message->getMessage(), 401);
            }

            if ($photo->is_thumb) { 
                $message = new ApiMessages('Não é possível remover foto de Thumb!');

                return response()->json($message->getMessage(), 401);
            }

            Storage::disk('public')->delete($photo->photo);
            $photo->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Foto removida com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }
}
