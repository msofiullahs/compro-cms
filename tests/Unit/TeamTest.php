<?php

namespace Tests\Unit;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TeamTest extends TestCase
{

    public function test_index(): void
    {
        $user = User::find(1);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->get('/api/teams');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data'
            ]);
    }

}
