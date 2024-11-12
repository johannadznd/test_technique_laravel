<?php

namespace App\Http\Requests\Profil;

use App\DTO\Profil\ProfilDto;
use Illuminate\Foundation\Http\FormRequest;

class ProfilRequest extends FormRequest
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

        if (request()->isMethod('POST')) {
            return [
                'lastName' => 'required|string|max:255',
                'firstName' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status' => 'required|in:inactif,en_attente,actif',
            ];
        }

        return [
            'lastName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:inactif,en_attente,actif',
        ];
    }

    public function messages()
    {

        if (request()->isMethod('POST')) {

            return [
                'lastName.required' => 'Le nom est requis',
                'firstName.required' => 'Le prénom est requis',
                'image.required' => 'L\'image est requise',
                'status.required' => 'Le statut est requis',
                'status.in' => 'Le statut doit être l\'un des suivants : inactif, en_attente, actif'
            ];
        }

        return [
            'lastName.required' => 'Le nom est requis',
            'firstName.required' => 'Le prénom est requis',
            'status.required' => 'Le statut est requis',
            'status.in' => 'Le statut doit être l\'un des suivants : inactif, en_attente, actif'
        ];
    }

    public function toDTO(): ProfilDto
    {
        // Validation des données
        $validatedData = $this->validated();

        // Retourner un DTO avec les données validées
        return new ProfilDto(
            lastName: $validatedData['lastName'],
            firstName: $validatedData['firstName'],
            image: $validatedData['image'] ?? null,
            status: $validatedData['status']
        );
    }
}
