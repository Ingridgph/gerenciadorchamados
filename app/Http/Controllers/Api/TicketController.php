<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChamadoCreateRequest;
use App\Http\Requests\ChamadoUpdateStatusRequest;
use App\Http\Requests\ListChamadoRequest;
use App\Http\Resources\ChamadoResource;
use App\Models\Chamado;
use App\Services\ChamadoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly ChamadoService $service,
    ) {}

    public function index(ListChamadoRequest $request): AnonymousResourceCollection
    {
        $chamados = Chamado::with('solicitante', 'responsavel', 'latestLog')
            ->when($request->validated('search'), function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('titulo', 'like', "%{$search}%")
                        ->orWhere('descricao', 'like', "%{$search}%");
                });
            })
            ->when($request->validated('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->validated('prioridade'), fn ($q, $prioridade) => $q->where('prioridade', $prioridade))
            ->latest()
            ->paginate(10);

        return ChamadoResource::collection($chamados);
    }

    public function show(Chamado $chamado): ChamadoResource
    {
        $chamado->load('solicitante', 'responsavel', 'latestLog');

        return ChamadoResource::make($chamado);
    }

    public function store(ChamadoCreateRequest $request): ChamadoResource
    {
        $chamado = $this->service->create($request);

        return ChamadoResource::make($chamado->load('solicitante'));
    }

    public function updateStatus(ChamadoUpdateStatusRequest $request, Chamado $chamado): ChamadoResource
    {
        $chamado = $this->service->updateStatus($request, $chamado);

        return ChamadoResource::make($chamado->load('solicitante', 'responsavel'));
    }

    public function destroy(Chamado $chamado)
    {
        $this->authorize('delete', $chamado);

        $chamado->delete();

        return response()->json(null, 204);
    }
}
