<?php

namespace App\Services;

use App\Models\CarreraGrado;
use Illuminate\Support\Facades\Log;

class CarreraGradoService
{
    public function getAll()
    {
        return CarreraGrado::all();
    }

    public function crearCarreraGrado($id_carrera, $id_grado)
    {
        try {
            $carreraGradoModel = new CarreraGrado();
            $carreraGradoModel->id_carrera = $id_carrera;
            $carreraGradoModel->id_grado = $id_grado;
            $carreraGradoModel->save();

            return response()->json($carreraGradoModel, 201);
        } catch (\Exception $e) {
            Log::error('Error al guardar CarreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar CarreraGrado'], 500);
        }
    }

    public function findById($id)
    {
       
    }

    public function update()
    {
       
    }

    public function delete($id)
    {
      
    }
}
