<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name'    => 'required',
            'email'   => 'required|email|unique:users,email',
            'password'=> 'required|min:6'
        ];
    }

     public function failedValidation(Validator $validator)
    {
        // la reponse pour les utilisateurs de l'API
        throw new HttpResponseException(response()->json([
            'success'    =>false,
            'status'     =>422,
            'message'    =>'Erreur de validation',
            'errorsList' =>$validator->errors()
        ]));
    }
    public function messages()
    {
        return [
            // ce code va retourner un message 
            'name.required'    =>'Un nom doit être fourni.',
            'email.required'   =>'Une addresse email doit être fourni.',
            'email.unique'     =>'Cet email est déjà utilisé.',
            'password.required'=>'Un mot de passe doit être fourni.'
        ];
    }
}
