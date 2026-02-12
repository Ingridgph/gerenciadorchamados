<?php

namespace App\Models;

use App\Enums\ChamadoPrioridadeEnum;
use App\Enums\ChamadoStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chamado extends Model
{
    use SoftDeletes;

    protected $table = 'chamado';

    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'prioridade',
        'solicitante_id',
        'responsavel_id',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
            'prioridade' => ChamadoPrioridadeEnum::class,
            'status' => ChamadoStatusEnum::class,
        ];
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
