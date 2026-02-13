<?php

namespace Database\Factories;

use App\Enums\ChamadoPrioridadeEnum;
use App\Enums\ChamadoStatusEnum;
use App\Models\Chamado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChamadoFactory extends Factory
{
    protected $model = Chamado::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(ChamadoStatusEnum::cases());
        $prioridade = $this->faker->randomElement(ChamadoPrioridadeEnum::cases());

        return [
            'titulo' => $this->faker->sentence(3),
            'descricao' => $this->faker->paragraph(5),
            'status' => $status,
            'prioridade' => $prioridade,
            'solicitante_id' => User::factory(),
            'responsavel_id' => null,
        ];
    }
}
