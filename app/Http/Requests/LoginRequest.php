<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6'

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
            'email.required'   =>'Un email doit être fourni.',
            'email.exists'     =>'Cet email n\'existe pas dans notre base de données.',
            'email.email'      =>'Le format de l\'email est invalide.',
            'password.required'=>'Un mot de passe doit être fourni.'
        ];
    }
}
