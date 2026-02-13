<?php

namespace Database\Seeders;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChamadoSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        Chamado::factory()->count(10)->make()->each(function ($chamado) use ($users) {
            $chamado->solicitante_id = $users->random()->id;
            $chamado->save();
        });
    }
}
