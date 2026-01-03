<?php
  
namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;

  
class AuthController extends Controller
{
    public function listUser(Request $request)
    {
        //liste des utilisateurs 
        return response()->json(User::all());
    }
    /**
     * Register a User.
     * Créer un utilisateur
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
  
        if($validator->fails()){
           // return response()->json($validator->errors()->toJson(), 400);
           // Échec de la connexion
              return response()->json([
                'success' => false,
                'message' => 'Les informations d\'identification sont invalides.'
            ], 401);
        }
  
        $user = new User;
        $user->name     = request()->name;
        $user->email    = request()->email;
        $user->password = bcrypt(request()->password);
        $user->save();
  
        //return response()->json($user, 201);
         return response()->json([
            'success'      => true,
            'message'      => 'Utilisateur enregistré avec succès.',
            'user'         => $user
        ], 201);
    }
  
  
    /**
     * Obtenir in JWT à partir des identifiants fournis.
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Les informations d\'identification sont invalides.',
                'access_token' => ''
            ], 401);
        }
  
        return $this->respondWithToken($token);
    }
  
    /**
     * Get the authenticated User.
     * Obtenir l'utilisateur authentifié
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     * Déconnecter l'utilisateur (invalider le jeton)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
  
        return response()->json(['message' => 'Déconnexion réussie']);
    }
  
    /**
     * Refresh a token.
     * Actualiser le jeton
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
  
    /**
     * Get the token array structure.
     * Obtenir la structure du tableau de jetons
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60 //minutes
        ]);
    }

    
}
