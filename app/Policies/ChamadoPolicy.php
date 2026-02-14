<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\User;

class ChamadoPolicy
{
    public function delete(User $user, Chamado $chamado): bool
    {
        return (bool) $user->admin;
    }
}
