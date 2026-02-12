<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadoCreateRequest;
use App\Http\Requests\ChamadoUpdateStatusRequest;
use App\Http\Requests\ListChamadoRequest;
use App\Services\ChamadoService;

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
        return $this->chamadoService->findById($id);
    }

    public function store(ChamadoCreateRequest $request)
    {
        return $this->chamadoService->create($request);
    }

    public function update(ChamadoUpdateStatusRequest $request, string $id)
    {
        return $this->chamadoService->update($request, $id);
    }

    public function destroy(string $id)
    {
        $this->chamadoService->delete($id);

        return response()->noContent();
    }
}
