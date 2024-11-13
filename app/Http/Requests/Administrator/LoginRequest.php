<?php

namespace App\Http\Requests\Administrator;

use Illuminate\Foundation\Http\FormRequest;
use Src\Domain\Administrator\Dtos\LoginDTO;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Le mail est requis',
            'password.required' => 'Le mot de passe est requis',
        ];
    }

    public function toDTO(): LoginDTO
    {
        // Validation des données
        $validatedData = $this->validated();

        // Retourner un DTO avec les données validées
        return new LoginDTO(
            email: $validatedData['email'],
            password: $validatedData['password']
        );
    }
}
