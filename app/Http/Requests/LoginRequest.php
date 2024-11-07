<?php

namespace App\Http\Requests;

use App\DTO\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

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

    public function toDTO(): LoginDTO
    {
        // Validation des données
        $validatedData = $this->validate($this->rules());

        // Retourner un DTO avec les données validées
        return new LoginDTO(
            email: $validatedData['email'],
            password: $validatedData['password']
        );
    }
}
