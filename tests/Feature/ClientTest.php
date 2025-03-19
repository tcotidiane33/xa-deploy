<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_client()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/clients', [
            'name' => 'Client Test',
            'responsable_paie_id' => $user->id,
            'gestionnaire_principal_id' => $user->id,
            'status' => 'actif',
            'nb_bulletins' => 100,
            'ville' => 'Paris',
            'dirigeant_nom' => 'Dupont',
            'dirigeant_telephone' => '0123456789',
            'dirigeant_email' => 'dupont@example.com',
            'contact_paie_nom' => 'Martin',
            'contact_paie_prenom' => 'Jean',
            'contact_paie_telephone' => '0123456789',
            'contact_paie_email' => 'jean.martin@example.com',
            'contact_compta_nom' => 'Durand',
            'contact_compta_prenom' => 'Marie',
            'contact_compta_telephone' => '0123456789',
            'contact_compta_email' => 'marie.durand@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Client créé avec succès',
                'client' => [
                    'name' => 'Client Test',
                    'status' => 'actif',
                    'ville' => 'Paris',
                ]
            ]);

        $this->assertDatabaseHas('clients', [
            'name' => 'Client Test',
            'status' => 'actif',
        ]);
    }

    /** @test */
    public function it_can_update_a_client()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create();

        $response = $this->putJson("/api/clients/{$client->id}", [
            'name' => 'Client Test Updated',
            'status' => 'inactif',
            'ville' => 'Lyon',
            'dirigeant_nom' => 'Dupont Updated',
            'dirigeant_telephone' => '0987654321',
            'dirigeant_email' => 'dupont.updated@example.com',
            'responsable_paie_id' => $user->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Client mis à jour avec succès',
                'client' => [
                    'name' => 'Client Test Updated',
                    'status' => 'inactif',
                    'ville' => 'Lyon',
                ]
            ]);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Client Test Updated',
            'status' => 'inactif',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_client()
    {
        $response = $this->postJson('/api/clients', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'status']);
    }

    /** @test */
    public function it_validates_required_fields_when_updating_a_client()
    {
        $client = Client::factory()->create();

        $response = $this->putJson("/api/clients/{$client->id}", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'status']);
    }
}
