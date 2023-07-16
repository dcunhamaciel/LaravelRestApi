<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Api\Messages\ApiMessages;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->user->paginate(10);

        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            
            return response()->json($user, 200);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        if (!$request->has('password') || !$request->get('password')) {
            $message = new ApiMessages('É necessário informa uma senha para o usuário!');

            return response()->json($message->getMessage(), 401);   
        }

        try {
            $data['password'] = bcrypt($data['password']);
            $user = $this->user->create($data);

            return response()->json([
                'data' => [
                    'msg' => 'Usuário cadastrado com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $data = $request->all();

        if (!$request->has('password')) {
            if ($request->get('password')) {
                $data['password'] = bcrypt($data['password']);
            } else {
                unset($data['password']);
            }
        }

        try {
            $user = $this->user->findOrFail($id);
            $user->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'Usuário atualizado com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->delete($id);

            return response()->json([
                'data' => [
                    'msg' => 'Usuário removido com sucesso!'
                ]
            ]);
        } catch(\Exception $error) {
            $message = new ApiMessages($error->getMessage());

            return response()->json($message->getMessage(), 401);
        }
    }
}