<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    protected string $role;
    protected string $token;
    protected $organization;

    public function __construct($resource, $role, $token, $organization = null)
    {
        parent::__construct($resource);

        $this->role = $role;
        $this->token = $token;
        $this->organization = $organization;
    }

    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Email verified successfully',

            'user' => new UserResource($this),

            'role' => strtolower($this->role),

            // 🔥 only include for client
            'organization' => $this->when(
                strtolower($this->role) === 'client',
                fn () => new OrganizationResource($this->organization)
            ),

            'token' => $this->token,
        ];
    }
}
