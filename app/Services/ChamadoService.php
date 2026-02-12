<?php

namespace App\Services;

use App\Http\Requests\ChamadoCreateRequest;
use App\Http\Requests\ChamadoUpdateStatusRequest;
use App\Http\Requests\ListChamadoRequest;
use App\Http\Resources\ChamadoResource;
use App\Models\Chamado;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChamadoService
{
    public function list(ListChamadoRequest $request): AnonymousResourceCollection
    {
        $chamados = Chamado::query()
            ->when($request->status, fn ($q, $status) => $q->where('status', $status)
            )
            ->when($request->prioridade, fn ($q, $prioridade) => $q->where('prioridade', $prioridade)
            )
            ->paginate(10);

        return ChamadoResource::collection($chamados);
    }

    public function findById(string $id): ChamadoResource
    {
        $chamado = Chamado::findOrFail($id);

        return ChamadoResource::make($chamado);
    }

    public function create(ChamadoCreateRequest $request): ChamadoResource
    {
        $chamado = Chamado::create($request->validated());

        return ChamadoResource::make($chamado);
    }

    public function updateStatus(ChamadoUpdateStatusRequest $request, string $id): ChamadoResource
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
