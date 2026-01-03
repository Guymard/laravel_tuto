<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditPosteRequest extends FormRequest
{
    /**
     *Déterminez si l'utilisateur est autorisé à effectuer cette demande.
     */
    public function authorize(): bool
    {
       // ici on autorise tout le monde : true(vrai)/false(faux)
        return true;
    }

    /**
     * Obtenez les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            // required : c'est une règle de validation, càd il faut que le champ soit fourni
            'titre' => 'required'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        // la reponse pour les utilisateurs de l'API
        throw new HttpResponseException(response()->json([
            'success'    =>false,
            'error'      =>true,
            'message'    =>'Erreur de validation',
            'errorsList' =>$validator->errors()
        ]));
    }
    public function messages()
    {
        return [
            // ce code va retourner un message 
            'titre.required'=>'Un titre doit être fourni.'
        ];
    }
}
