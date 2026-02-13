<?php

namespace Database\Factories;

use App\Enums\ChamadoPrioridadeEnum;
use App\Enums\ChamadoStatusEnum;
use App\Models\Chamado;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChamadoFactory extends Factory
{
    protected $model = Chamado::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement([
            ChamadoStatusEnum::ABERTO,
            ChamadoStatusEnum::EM_ANDAMENTO,
            ChamadoStatusEnum::RESOLVIDO,
        ]);

        $prioridade = $this->faker->randomElement([ChamadoPrioridadeEnum::BAIXA, ChamadoPrioridadeEnum::MEDIA, ChamadoPrioridadeEnum::ALTA]);

        return [
            'titulo' => $this->faker->sentence(3),
            'descricao' => $this->faker->paragraph(),
            'status' => $status,
            'prioridade' => $prioridade,
            'solicitante_id' => 1,
        ];
    }
}
