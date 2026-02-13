<?php

namespace App\Http\Controllers;

use App\Enums\ChamadoPrioridadeEnum;
use App\Enums\ChamadoStatusEnum;
use App\Http\Requests\ChamadoCreateRequest;
use App\Http\Requests\ChamadoUpdateStatusRequest;
use App\Http\Requests\ListChamadoRequest;
use App\Services\ChamadoService;
use Illuminate\Support\Facades\Auth;

class ChamadoController extends Controller
{
    public function __construct(
        private ChamadoService $chamadoService
    ) {}

    public function index(ListChamadoRequest $request)
    {
        return $this->chamadoService->list($request);
    }

    public function show(string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $this->authorize('view', $chamado);
        
        return $this->chamadoService->findById($id);
    }

    public function store(ChamadoCreateRequest $request)
    {
        // Auto-fill required fields with defaults
        $validated = $request->validated();
        $validated['solicitante_id'] ??= Auth::id();
        $validated['status'] ??= ChamadoStatusEnum::ABERTO->value;
        $validated['prioridade'] ??= ChamadoPrioridadeEnum::MEDIA->value;

        return $this->chamadoService->create($validated);
    }

    public function updateStatus(ChamadoUpdateStatusRequest $request, string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $this->authorize('update', $chamado);
        
        return $this->chamadoService->updateStatus($request, $id);
    }

    public function destroy(string $id)
    {
        $chamado = Chamado::findOrFail($id);
        $this->authorize('delete', $chamado);
        
        $this->chamadoService->delete($id);

        return response()->noContent();
    }
}
