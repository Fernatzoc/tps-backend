<?php

namespace App\Http\Controllers;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required'
        ]);

        if($validator->fails()){
//            return response()->json($validator->errors()->toJson(),400);
            return response()->json([
                'ok' => false,
                'message' => $validator->errors()
            ],400);

        }

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'password' => bcrypt($request->input('password')),
        ]);

        $user->save();

        return response()->json([
            'ok' => true,
            'user' => $user,
            'message' => 'Usuario creado correctamente'
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Usuario o correo invalidos'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        }

        return response()->json([
            'ok' => true,
            'user' => auth()->user(),
            'token' => $token
        ], 201);
    }

    public function getUser(){
        $user = auth('api')->user();
        return response()->json(['user'=>$user], 201);
    }

    public function getAllUsers(){
        return response()->json([
            'total' => User::count(),
            'users' => User::all()
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $newToken = auth()->refresh();
        $id = auth()->user()->id;
        $name = auth()->user()->name;
        $role = auth()->user()->role;

        return response()->json([
            'ok' => true,
            'id' => $id,
            'name' => $name,
            'role' => $role,
            'token' => $newToken
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return  $user;
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;

        $user->save();

        return $user;
    }

    public function destroy($id)
    {
        $user = User::destroy($id);
        return response()->json([
           'message' => 'Usuario eliminado correctamente'
        ]);
    }

}
