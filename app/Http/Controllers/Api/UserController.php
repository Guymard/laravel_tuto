<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Exception;
use App\Http\Requests\LoginRequest;

  

class UserController extends Controller
{
    public function register1(RegisterUser $request){
        //dd('ok');
        try {
            $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        //dd(['success'=>true,'message'=>'Utilisateur enregistré avec succès.'
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur enregistré avec succès.',
            'user'    => $user
        ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de l\'enregistrement de l\'utilisateur.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }   
        
    public function login1(LoginRequest $request){
        //code de connexion à venir

        if(auth()->attempt($request->only('email','password'))){
        $user = auth()->user();
        //dd($user);//debugger
        $token = $user->createToken('Mon_Token_secret')->plainTextToken;
        return response()->json([
            'success'      => true,
            'message'      => 'Connexion réussie.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user
        ], 200);
        } else{
            // Échec de la connexion
        return response()->json([
            'success' => false,
            'message' => 'Les informations d\'identification sont invalides.'
        ], 401);
    }
}
}
 