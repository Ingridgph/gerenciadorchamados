<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChamadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'titulo' => $this->titulo,
            'descricao' => $this->descricao,

            'status' => $this->status?->value,
            'prioridade' => $this->prioridade?->value,

            'resolved_at' => $this->resolved_at?->toISOString(),

            'solicitante' => [
                'id' => $this->solicitante?->id,
                'name' => $this->solicitante?->name,
                'email' => $this->solicitante?->email,
            ],

            'responsavel' => $this->when(
                $this->responsavel,
                fn () => [
                    'id' => $this->responsavel->id,
                    'name' => $this->responsavel->name,
                    'email' => $this->responsavel->email,
                ]
            ),

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
