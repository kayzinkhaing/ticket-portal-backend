<?php
// app/Services/Positions.php
namespace App\Services;

use App\Contracts\ClientProfileInterface;

class ClientProfiles extends Common
{
    protected $clientProfile;

    public function __construct(ClientProfileInterface $clientProfile)
    {
        parent::__construct($clientProfile);

        $this->clientProfile = $clientProfile;
    }
}
