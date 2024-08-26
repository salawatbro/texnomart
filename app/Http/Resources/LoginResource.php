<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    public string $token;

    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'user'=> UserResource::make($this),
            'token'=> $this->token
        ];
    }
}
