<?php

namespace App\Services;

use App\Models\Chamado;
use App\Http\Requests\ChamadoRequest;
use App\Http\Requests\ListQueryRequest;
use App\Http\Resources\ChamadoResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChamadoService
{
    public function list(ListQueryRequest $request): AnonymousResourceCollection
    {
        $chamados = Chamado::query()
            ->when($request->status, fn ($q, $status) =>
                $q->where('status', $status)
            )
            ->when($request->prioridade, fn ($q, $prioridade) =>
                $q->where('prioridade', $prioridade)
            )
            ->paginate(10);

        return ChamadoResource::collection($chamados);
    }

    public function findById(string $id): ChamadoResource
    {
        $chamado = Chamado::findOrFail($id);

        return ChamadoResource::make($chamado);
    }

    public function create(ChamadoRequest $request): ChamadoResource
    {
        $chamado = Chamado::create($request->validated());

        return ChamadoResource::make($chamado);
    }

    public function update(ChamadoRequest $request, string $id): ChamadoResource
    {
        $chamado = Chamado::findOrFail($id);
        $chamado->update($request->validated());

        return ChamadoResource::make($chamado);
    }

    public function delete(string $id): void
    {
        $chamado = Chamado::findOrFail($id);
        $chamado->delete();
    }
}
