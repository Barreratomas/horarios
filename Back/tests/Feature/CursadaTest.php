<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\horarios\Cursada;
use Database\Factories\CursadaFactory;
use Illuminate\Support\Facades\Log;

class CursadaTest extends TestCase
{

    use RefreshDatabase;

    // Test para listar todas las cursadas (GET /api/horarios/cursadas)
    /** @test */
    public function it_returns_a_list_of_cursadas()
    {

        // Crear algunas cursadas de prueba
        CursadaFactory::new()->count(3)->create();

        // Hacer una solicitud GET al endpoint
        $response = $this->getJson('/api/horarios/cursadas');

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que la respuesta contiene las cursadas creadas
        $response->assertJsonCount(3);

        // Verificar la estructura de la respuesta JSON
        $response->assertJsonStructure([
            '*' => [
                'id_cursada',
                'inicio',
                'fin',
            ]
        ]);
    }

    // Test para obtener una cursada por ID (GET /api/horarios/cursadas/{id})
    /** @test */
    public function it_returns_a_specific_cursada()
    {
        // Crear una cursada de prueba
        $cursada = CursadaFactory::new()->create();

        // Hacer una solicitud GET al endpoint
        $response = $this->getJson("/api/horarios/cursadas/{$cursada->id_cursada}");

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que la respuesta contiene la cursada creada
        $response->assertJson([
            'id_cursada' => $cursada->id_cursada,
            'inicio' => $cursada->inicio,
            'fin' => $cursada->fin,
        ]);
    }

    // Test para crear una nueva cursada (POST /api/horarios/cursadas/guardar)
    /** @test */
    public function it_creates_a_new_cursada()
    {
        // Datos de la nueva cursada
        $cursadaData = [
            'inicio' => '2023-10-01',
            'fin' => '2023-12-15',
        ];

        // Hacer una solicitud POST al endpoint
        $response = $this->postJson('/api/horarios/cursadas/guardar', $cursadaData);

        // Verificar que la respuesta tiene un código de estado 201
        $response->assertStatus(201);

        // Verificar que la respuesta contiene los datos de la cursada creada
        $response->assertJson($cursadaData);

        // Verificar que la cursada se ha guardado en la base de datos
        $this->assertDatabaseHas('cursada', $cursadaData);
    }

    // Test para actualizar una cursada existente (PUT /api/horarios/cursadas/actualizar/{id})
    /** @test */
    public function it_updates_an_existing_cursada()
    {
        // Crear una cursada de prueba
        $cursada = CursadaFactory::new()->create();

        // Datos actualizados
        $updatedData = [
            'inicio' => '2023-11-01',
            'fin' => '2024-01-15',
        ];

        // Hacer una solicitud PUT al endpoint
        $response = $this->putJson("/api/horarios/cursadas/actualizar/{$cursada->id_cursada}", $updatedData);

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que la respuesta contiene los datos actualizados
        $response->assertJson($updatedData);

        // Verificar que la cursada se ha actualizado en la base de datos
        $this->assertDatabaseHas('cursada', $updatedData);
    }

    // Test para eliminar una cursada (DELETE /api/horarios/cursadas/eliminar/{id})
    /** @test */
    public function it_deletes_an_existing_cursada()
    {
        // Crear una cursada de prueba
        $cursada = CursadaFactory::new()->create();

        // Hacer una solicitud DELETE al endpoint
        $response = $this->deleteJson("/api/horarios/cursadas/eliminar/{$cursada->id_cursada}");

        // Verificar que la respuesta tiene un código de estado 200
        $response->assertStatus(200);

        // Verificar que la respuesta contiene el mensaje de éxito
        $response->assertJson(['message' => 'Cursada eliminada correctamente']);

        // Verificar que la cursada ya no existe en la base de datos
        $this->assertDatabaseMissing('cursada', [
            'id_cursada' => $cursada->id_cursada,
        ]);
    }

    // Test para manejar errores al obtener una cursada que no existe
    /** @test */
    public function it_returns_404_when_cursada_not_found()
    {
        // Hacer una solicitud GET al endpoint con un ID que no existe
        $response = $this->getJson('/api/horarios/cursadas/999');

        // Verificar que la respuesta tiene un código de estado 404
        $response->assertStatus(404);

        // Verificar que la respuesta contiene el mensaje de error
        $response->assertJson(['error' => 'No existe la cursada']);
    }

    // Test para manejar errores al actualizar una cursada que no existe
    /** @test */
    public function it_returns_404_when_updating_non_existent_cursada()
    {
        // Datos actualizados
        $updatedData = [
            'inicio' => '2023-11-01',
            'fin' => '2024-01-15',
        ];

        // Hacer una solicitud PUT al endpoint con un ID que no existe
        $response = $this->putJson('/api/horarios/cursadas/actualizar/999', $updatedData);

        // Verificar que la respuesta tiene un código de estado 404
        $response->assertStatus(404);

        // Verificar que la respuesta contiene el mensaje de error
        $response->assertJson(['error' => 'Cursada no encontrada']);
    }

    // Test para manejar errores al eliminar una cursada que no existe
    /** @test */
    public function it_returns_404_when_deleting_non_existent_cursada()
    {
        // Hacer una solicitud DELETE al endpoint con un ID que no existe
        $response = $this->deleteJson('/api/horarios/cursadas/eliminar/999');

        // Verificar que la respuesta tiene un código de estado 404
        $response->assertStatus(404);

        // Verificar que la respuesta contiene el mensaje de error
        $response->assertJson(['error' => 'Cursada no encontrada']);
    }
}
