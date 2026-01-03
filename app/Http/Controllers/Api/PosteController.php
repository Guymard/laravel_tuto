<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreationPosteRequest;
use App\Http\Requests\EditPosteRequest;
use App\Models\Poste;
use Exception;
use Illuminate\Http\Request;


class PosteController extends Controller
{
    public function index()
    {
      /*
       try..catch : il sert à intercepter les exceptions (erreurs) afin d'eviter que 
       l'application plate et pour gérer proprement less erreurs.
      */
       try{

        $query   = Poste::query();

        $parpage = 2;
        $page    = request()->input('page', 1);
      
        // Recherche de poste et affichage par page
        $search  = request()->input('search');
        if ($search) {
            $query->whereRaw("titre LIKE '%". $search ."%'");
        }

        $total  = $query->count();
        $resultat = $query->offset(($page - 1) * $parpage)->limit($parpage)->get();

         return response()->json([
                'success'      => true,
                'page_en_cours'=> $parpage,
                'derniere_page'=> ceil($total / $parpage),
                'item'         => $resultat,
                'total'        => $total
        ], 200);
        } catch(Exception $e){
            return Response()->json($e);
        }
    }

    //Creation du poste
    public function store(CreationPosteRequest $request){
      
        //try fait une tentative d'execution du code
        try{


        $Poste = new Poste();

        $Poste->titre       = $request->titre;
        $Poste->description = $request->description;
        $Poste->user_id     = auth()->user()->id;

        $Poste->save();
       
        //Reponse json aux clients de l'API
        return response()->json([
            'success' => true, //200
            'message' => 'Poste créé avec succès',
            'data'    => $Poste
     ], 200);

         // Code 200 : requete traitée avec succès

       } catch(Exception $e){
         //CATCH : attrape les erreurs

        //La reponse en cas d'erreur serveur ou autre
        return Response()->json($e);
       }
    }

    // Modification du poste : Methode PUT 1
    
    public function update(EditPosteRequest $request, $id){
        $poste = Poste::find($id);
        //dd($poste); pour faire le test 

        $poste->titre       =$request->titre;
        $poste->description =$request->description;
        if($poste->user_id== auth()->user()->id){
             $poste->save();

        }else{
            return response()->json([
                     'message' => 'Vous n\'etes pas l\'auteur de ce poste',
                ], 422);
        }

       

    }
    

    // Modification du poste : Methode PUT 2
    public function update1(EditPosteRequest $request, Poste $poste){
       //dd($poste);
       try{
         $poste->titre       = $request->titre;
         $poste->description = $request->description;

         $poste->save();
         return response()->json([
        'message' => 'Poste mis à jour avec succès',
        'data'    => $poste
         ], 200);
       }catch(Exception $e){
           //CATCH : attrape les erreurs

        //La reponse en cas d'erreur serveur ou autre
        return Response()->json($e);
       }

     
    }

    // Suppression du poste : Methode DELETE
    public function delete(Poste $poste){
        try{
            
            if($poste->user_id== auth()->user()->id){
                $poste->delete();
               
                return response()->json([
                   'message' => 'Poste supprimé avec succès',
                ], 200);
        
            }else{
          
                return response()->json([
                     'message' => 'Poste non supprimé, soit introuvable, ou vous n\'etes pas l\'auteur de ce poste',
                ], 422);
            }
    
        }catch(Exception $e){
            return response()->json($e);
         }    
    }
}