<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\User;

class ChamadoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Chamado $chamado): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Chamado $chamado): bool
    {
        return $user->admin || $chamado->solicitante_id === $user->id;
    }

    public function delete(User $user, Chamado $chamado): bool
    {
        return (bool) $user->admin;
    }
}
