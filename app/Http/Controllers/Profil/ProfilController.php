<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilRequest;
use App\Services\ProfilService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfilController extends Controller
{


    protected ProfilService $profilService;

    public function __construct(ProfilService $profilService)
    {
        $this->profilService = $profilService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfilRequest $request)
    {
        try {

            $profilDTO = $request->toDTO();

            $imageName = $this->profilService->storeImage($request->file('image'));

            $this->profilService->createProfil($profilDTO, $imageName);

            return response()->json(
                [
                    'message' => "Le profil a bien été créé"
                ],
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Un problème est survenu lors de la création du profil',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
