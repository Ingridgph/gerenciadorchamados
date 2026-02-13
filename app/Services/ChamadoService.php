<?php

namespace App\Services;

use App\Enums\ChamadoStatusEnum;
use App\Http\Requests\ChamadoCreateRequest;
use App\Http\Requests\ChamadoUpdateStatusRequest;
use App\Http\Requests\ListChamadoRequest;
use App\Http\Resources\ChamadoResource;
use App\Models\Chamado;
use App\Models\ChamadoLog;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ChamadoService
{
    public function list(ListChamadoRequest $request): AnonymousResourceCollection
    {
        $chamados = Chamado::query()
            ->when($request->status, fn ($q, $status) => $q->where('status', $status)
            )
            ->when($request->prioridade, fn ($q, $prioridade) => $q->where('prioridade', $prioridade)
            )->when(
                $request->search,
                function ($q, $search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('titulo', 'like', "%{$search}%")
                            ->orWhere('descricao', 'like', "%{$search}%");
                    });
                }
            )
            ->paginate(10);

        return ChamadoResource::collection($chamados);
    }

    public function findById(string $id): ChamadoResource
    {
        $chamado = Chamado::findOrFail($id);

        return ChamadoResource::make($chamado);
    }

    /**
     * Create chamado from validated array data.
     *
     * @param array $data
     */
    public function create(array $data): ChamadoResource
    {
        $chamado = Chamado::create($data);

        return ChamadoResource::make($chamado);
    }

    public function updateStatus(ChamadoUpdateStatusRequest $request, string $id): ChamadoResource
    {
        $chamado = Chamado::findOrFail($id);

        $statusAnterior = $chamado->status;
        $novoStatus = $request->status;

        $chamado->status = $novoStatus;

        if ($novoStatus === ChamadoStatusEnum::RESOLVIDO->value) {
            $chamado->resolved_at = now();
        }

        $chamado->save();

        ChamadoLog::create([
            'chamado_id' => $chamado->id,
            'de' => $statusAnterior,
            'para' => $novoStatus,
            'user_id' => Auth::id(),
        ]);

        return ChamadoResource::make($chamado);
    }

    public function delete(string $id): void
    {
        $chamado = Chamado::findOrFail($id);

        $chamado->delete();
    }
}
