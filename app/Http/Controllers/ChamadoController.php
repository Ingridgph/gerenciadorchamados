<?php

namespace App\Http\Controllers;

use App\Services\ChamadoService;
use App\Http\Requests\ChamadoRequest;
use App\Http\Requests\ListChamadoRequest;

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

    public function store(ChamadoRequest $request)
    {
        return $this->chamadoService->create($request);
    }

    public function update(ChamadoRequest $request, string $id)
    {
        return $this->chamadoService->update($request, $id);
    }

    public function destroy(string $id)
    {
        $this->chamadoService->delete($id);

        return response()->noContent();
    }
}
