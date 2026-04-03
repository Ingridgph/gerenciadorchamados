<?php

namespace App\Http\Controllers\Web;

use App\Enums\ChamadoStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Chamado;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $statusCounts = Chamado::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total' => $statusCounts->sum(),
            'aberto' => $statusCounts->get(ChamadoStatusEnum::ABERTO->value, 0),
            'em_andamento' => $statusCounts->get(ChamadoStatusEnum::EM_ANDAMENTO->value, 0),
            'resolvido' => $statusCounts->get(ChamadoStatusEnum::RESOLVIDO->value, 0),
        ];

        $recentChamados = Chamado::with('solicitante')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentChamados'));
    }
}
