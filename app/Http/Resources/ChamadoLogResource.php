<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChamadoLogResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'de' => $this->de,
            'para' => $this->para,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ];
    }
}
