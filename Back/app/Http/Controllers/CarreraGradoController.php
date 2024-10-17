<?php

namespace App\Http\Controllers;

use App\Models\CarreraGrado;
use App\Services\CarreraGradoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CarreraGradoController extends Controller
{
    protected $carreraGradoService;

    public function __construct(CarreraGradoService $carreraGradoService)
    {
        $this->carreraGradoService = $carreraGradoService;
    }

    public function index()
    {
        $carreras = $this->carreraGradoService->getAll();
        return response()->json($carreras);
    }

    public function store(Request $request)
    {

        return $this->carreraGradoService->crearCarreraGrado($request->input('id_carrera'), $request->input('id_grado'));
    }

    public function show($id)
    {
      
    }

    public function update(Request $request, $id)
    {
        

        
    }

    public function destroy($id)
    {
       
    }
}
