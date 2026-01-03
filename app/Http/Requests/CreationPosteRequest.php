<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class CreationPosteRequest extends FormRequest
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
 