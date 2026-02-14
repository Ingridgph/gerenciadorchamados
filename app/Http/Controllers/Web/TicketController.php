<?php

namespace App\Http\Controllers\Web;

use App\Enums\ChamadoPrioridadeEnum;
use App\Enums\ChamadoStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Chamado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Chamado::with('solicitante');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('prioridade')) {
            $query->where('prioridade', $request->input('prioridade'));
        }

        $chamados = $query->latest()->paginate(10)->withQueryString();

        return view('chamados.index', compact('chamados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('chamados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|string',
        ]);

        $chamado = Chamado::create([
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'prioridade' => ChamadoPrioridadeEnum::from($validated['prioridade']),
            'status' => ChamadoStatusEnum::ABERTO,
            'solicitante_id' => Auth::id(),
        ]);

        return redirect()->route('chamados.index')->with('success', 'Chamado criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chamado $chamado)
    {
        $chamado->load('solicitante', 'responsavel');

        return view('chamados.show', compact('chamado'));
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, Chamado $chamado)
    {
        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $chamado->update([
            'status' => ChamadoStatusEnum::from($validated['status']),
            'responsavel_id' => Auth::id(), 
        ]);

        if ($validated['status'] === ChamadoStatusEnum::RESOLVIDO->value) {
            $chamado->update(['resolved_at' => now()]);
        }

        return redirect()->route('chamados.index')->with('success', 'Status atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chamado $chamado)
    {
        if (! Auth::user()->can('delete', $chamado)) {
            abort(403, 'Acesso não autorizado');
        }

        $chamado->delete();

        return redirect()->route('chamados.index')->with('success', 'Chamado excluído com sucesso!');
    }
}
