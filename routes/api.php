<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PosteController;
use App\Http\Controllers\AuthController;

/*
!----------------------------------------------------
! API Routes ! GETWAY
!----------------------------------------------------
*/

// Crée un lien qui permettra aux clients : Windev 


//Route::get('Poste', [PosteController::class, 'index']);

 // Créer  un Poste : Methode POST 
 //Route::post('Poste/create', [PosteController::class, 'store']);
 

/* Les deux Methodes PUT pour modifier ou mettre à jour un Poste :
   ----------------------------------------------------------------
*/

// Editer un Poste : Methode PUT 1 avec $id
//Route::put('Poste/edit/{id}',[PosteController::class, "update"]);


Route::get('/listPost',[PosteController::class, 'listPost']);

/*
Route::get('/hello', function (Request $request){
    //return response()->json(['message' => 'API is working']);
});
*/
 
 
Route::group([
    
    'middleware' => 'api',
    'prefix'     => 'auth'

], function ($router) {
    // Créer  un Poste : Methode POST  avec Authentification
    Route::post('/Poste/create', [PosteController::class, 'store'])->middleware('auth:api')->name('store');
  
    // Editer un Poste : Methode PUT 2 avec $poste ! laravel 12 avec Authentification
    Route::put('Poste/edit/{poste}',[PosteController::class, "update1"])->middleware('auth:api')->name('update1');

    // Methode DELETE pour supprimer un Poste avec Authentification
    Route::delete('Poste/delete/{poste}', [PosteController::class, 'delete'])->middleware('auth:api')->name('delete');

    //Methode GET pour recuperer un Poste par son id avec Authentification
    Route::get('Poste/{id}', [PosteController::class, 'show'])->middleware('auth:api')->name('show');

    //Methode GET pour recuperer la liste des postes actifs avec Authentification
    Route::get('Poste/actif', [PosteController::class, 'liste'])->middleware('auth:api')->name('liste');
    
    //Creation d'utilisateur
    Route::post('/register',[AuthController::class, 'register'])->name('register');

    //Se connecter avec l'utilisateur
    Route::post('/login',   [AuthController::class, 'login'])->name('login');
    
    // avec Authentification
    //Se Déconnecter
    Route::post('/logout',  [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');

    //Renouvellement du token
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');

    //S'identifier, voir l'utilisateur encours
    Route::post('/me',      [AuthController::class, 'me'])->middleware('auth:api')->name('me');

    Route::get('/listUser',[AuthController::class, 'listUser'])->middleware('auth:api')->name('listUser');

  //  Route::get('/listPost',[PosteController::class, 'listPost'])->middleware('auth:api')->name('listPost');
    Route::get('/Poste',   [PosteController::class, 'index'])->middleware('auth:api')->name('listPoste');
});

/*

Route::middleware('auth:sanctum')->group(function(){

     // Créer  un Poste : Methode POST 
    //Route::post('Poste/create', [PosteController::class, 'store']);

    Route::get('/user',function(Request $request){
        return $request->user();

    });
})
*/
    



