<?php

namespace App\Services;

use App\Enums\ChamadoStatusEnum;
use App\Http\Requests\ChamadoCreateRequest;
use App\Http\Requests\ChamadoUpdateStatusRequest;
use App\Models\Chamado;
use App\Models\ChamadoLog;
use Illuminate\Support\Facades\Auth;

class ChamadoService
{
    public function create(ChamadoCreateRequest $request): Chamado
    {
        return Chamado::create([
            'titulo' => $request->validated('titulo'),
            'descricao' => $request->validated('descricao'),
            'prioridade' => $request->validated('prioridade'),
            'status' => ChamadoStatusEnum::ABERTO,
            'solicitante_id' => Auth::id(),
        ]);
    }

    public function updateStatus(ChamadoUpdateStatusRequest $request, Chamado $chamado): Chamado
    {
        $statusAnterior = $chamado->status->value;
        $novoStatus = $request->validated('status');

        $chamado->update([
            'status' => ChamadoStatusEnum::from($novoStatus),
            'responsavel_id' => Auth::id(),
            'resolved_at' => $novoStatus === ChamadoStatusEnum::RESOLVIDO->value ? now() : $chamado->resolved_at,
        ]);

        ChamadoLog::create([
            'chamado_id' => $chamado->id,
            'de' => $statusAnterior,
            'para' => $novoStatus,
            'user_id' => Auth::id(),
        ]);

        return $chamado->fresh();
    }
}
