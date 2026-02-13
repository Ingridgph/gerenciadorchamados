<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\User;

class ChamadoPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Chamado $chamado): bool
    {
        // Apenas solicitante, responsável ou admin podem visualizar
        return $user->id === $chamado->solicitante_id ||
               $user->id === $chamado->responsavel_id ||
               $user->admin;
    }

    public function update(User $user, Chamado $chamado): bool
    {
        // Apenas solicitante ou admin podem editar
        return $user->id === $chamado->solicitante_id || $user->admin;
    }

    public function delete(User $user, Chamado $chamado): bool
    {
        // Apenas solicitante ou admin podem deletar
        return $user->id === $chamado->solicitante_id || $user->admin;
    }
}
