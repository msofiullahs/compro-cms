<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;
use Tests\TestCase;

class RemoveTeamMemberTest extends TestCase
{


    public function test_team_members_can_be_removed_from_teams(): void
    {
        $this->actingAs($user = User::find(1));

        $user->currentTeam->users()->attach(
            $otherUser = User::find(1), ['role' => 'admin']
        );

        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
            ->set('teamMemberIdBeingRemoved', $otherUser->id)
            ->call('removeTeamMember');

        $this->assertCount(0, $user->currentTeam->fresh()->users);
    }

    public function test_only_team_owner_can_remove_team_members(): void
    {
        $user = User::find(1);

        $user->currentTeam->users()->attach(
            $otherUser = User::find(1), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
            ->set('teamMemberIdBeingRemoved', $user->id)
            ->call('removeTeamMember')
            ->assertStatus(403);
    }
}
