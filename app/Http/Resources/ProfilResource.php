<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfilResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isAuth = auth('administrator')->check();

        $data = [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'image' => $this->image,

        ];

        if($isAuth){
            // On n'affiche le status que si on est authentifié
            $data['status'] = $this->status;
        }

        return $data;
    }
}
