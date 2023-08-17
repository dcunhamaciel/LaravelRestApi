<?php

namespace App\Exceptions;

use Exception;

class ResponseApi extends Exception
{
    /**
     * Retorna a mensagem para API
     * @return \Illuminate\Http\JsonResponse
     */
    public function render(): \Illuminate\Http\JsonResponse
    {
        $details = [];
        $data = json_decode($this->getMessage());
        if (!empty($data))
            foreach ($data as $key => $error)
                $details[$key] = $error[0];
        return response()->json([
            "message" => empty($data) ? $this->getMessage() : $details,
            "status" => $this->getCode()
        ], $this->getCode());
    }
}
