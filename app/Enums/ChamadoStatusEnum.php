<?php

namespace App\Enums;

enum ChamadoStatusEnum:string
{
    case ABERTO = 'aberto';
    case EM_ANDAMENTO = 'em_andamento';
    case RESOLVIDO = 'resolvido';
}
