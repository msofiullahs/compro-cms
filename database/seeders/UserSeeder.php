<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'test@mail.com';
        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->password = bcrypt('mypassword');
        $user->current_team_id = 1;
        $user->save();
    }
}
