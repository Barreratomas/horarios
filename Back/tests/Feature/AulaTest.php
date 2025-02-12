<?php

namespace Tests\Feature\Controllers\Horarios;

use Tests\TestCase;
use App\Models\Horarios\Aula;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Factories\AulaFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AulaTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Prueba para el endpoint GET /api/horarios/aulas (index).
     *
     * @return void
     */
    public function testIndex()
    {
        // Crear datos de prueba
        AulaFactory::new()->count(3)->create();

        // Hacer la solicitud GET al endpoint
        $response = $this->getJson('/api/horarios/aulas');

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que la respuesta contiene las aulas creadas
        $response->assertJsonCount(3);

        // Verificar la estructura de la respuesta JSON
        $response->assertJsonStructure([
            '*' => [
                'id_aula',
                'nombre',
                'capacidad',
                'tipo_aula',
            ]
        ]);

    }

    /**
     * Prueba para el endpoint GET /api/horarios/aulas/{id} (show).
     *
     * @return void
     */
    public function testShow()
    {
        // Crear un aula de prueba
        $aula = AulaFactory::new()->create();

        // Hacer la solicitud GET al endpoint con el ID del aula
        $response = $this->getJson("/api/horarios/aulas/{$aula->id_aula}");

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que la respuesta contiene la aula creada
        $response->assertJson([
            'id_aula' => $aula->id_aula,
            'nombre' => $aula->nombre,
            'capacidad' => $aula->capacidad,
            'tipo_aula' => $aula->tipo_aula,
        ]);

    }

    /**
     * Prueba para el endpoint POST /api/horarios/aulas/guardar (store).
     *
     * @return void
     */
    public function testStore()
    {
        // Datos de prueba para crear un aula
        $aulaData = [
            'nombre' => 'Aula 101',
            'capacidad' => 50,
            'tipo_aula' => 'Salón',
        ];

        // Hacer la solicitud POST al endpoint
        $response = $this->postJson('/api/horarios/aulas/guardar', $aulaData);

        // Verificar la respuesta
        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJsonStructure([
                     'id_aula',
                     'nombre',
                     'capacidad',
                     'tipo_aula',
                 ]);

        // Verificar que el aula se haya creado en la base de datos
        $this->assertDatabaseHas('aula', $aulaData);
    }

    /**
     * Prueba para el endpoint PUT /api/horarios/aulas/actualizar/{id} (update).
     *
     * @return void
     */
    public function testUpdate()
    {
        // Crear un aula de prueba
        $aula = AulaFactory::new()->create();

        // Datos de prueba para actualizar el aula (usar un nombre único)
        $updatedData = [
            'nombre' => 'Aula 103', // Nombre único
            'capacidad' => 60,
            'tipo_aula' => 'Laboratorio',
            'usuario' => 'admin', // Campo requerido para el log
            'detalles' => 'Actualización de aula', // Campo requerido para el log
        ];

        // Hacer la solicitud PUT al endpoint
        $response = $this->putJson("/api/horarios/aulas/actualizar/{$aula->id_aula}", $updatedData);

        // Si la respuesta es 422, imprimir el error
        if ($response->status() === 422) {
            Log::info($response->json()); // Imprime la respuesta JSON
        }

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que el aula se ha actualizado en la base de datos
        $this->assertDatabaseHas('aula', [
            'id_aula' => $aula->id_aula,
            'nombre' => $updatedData['nombre'],
            'capacidad' => $updatedData['capacidad'],
            'tipo_aula' => $updatedData['tipo_aula'],
        ]);
    }

    /**
     * Prueba para el endpoint DELETE /api/horarios/aulas/eliminar/{id} (destroy).
     *
     * @return void
     */
    public function testDestroy()
    {
        // Crear un aula de prueba
        $aula = AulaFactory::new()->create();

        // Verificar que el aula se haya creado correctamente
        $this->assertDatabaseHas('aula', [
            'id_aula' => $aula->id_aula,
        ]);

        // Datos de prueba para el log
        $logData = [
            'usuario' => 'admin', // Campo requerido para el log
            'detalles' => 'Eliminación de aula', // Campo requerido para el log
        ];

        // Hacer la solicitud DELETE al endpoint con los datos del log
        $response = $this->deleteJson("/api/horarios/aulas/eliminar/{$aula->id_aula}", $logData);

        // Si la respuesta es 404, imprimir el error
        if ($response->status() === 404) {
            Log::info($aula);
            Log::info($response->json()); // Imprime la respuesta JSON
        }

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que la respuesta contiene el mensaje de éxito
        $response->assertJson(['message' => 'Aula eliminada correctamente']);

        // Verificar que el aula se haya eliminado de la base de datos
        $this->assertDatabaseMissing('aula', [
            'id_aula' => $aula->id_aula,
        ]);
    }
}