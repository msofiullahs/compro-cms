<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : 1;

        $team = new Team();
        $team->user_id = $userId;
        $team->name = 'My First Team';
        $team->personal_team = true;
        $team->save();

        DB::table('team_user')->insert([
            'team_id'   => $team->id,
            'user_id'   => $userId,
            'role'      => 'admin',
        ]);
    }
}
