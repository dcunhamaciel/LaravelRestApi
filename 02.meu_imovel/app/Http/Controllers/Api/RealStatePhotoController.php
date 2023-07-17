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
}
