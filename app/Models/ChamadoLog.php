<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamadoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'chamado_id',
        'de',
        'para',
        'user_id',
    ];

    public function chamado()
    {
        return $this->belongsTo(Chamado::class, 'chamado_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
