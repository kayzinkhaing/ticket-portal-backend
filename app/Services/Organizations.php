<?php
// app/Services/Positions.php
namespace App\Services;

use App\Contracts\OrganizationInterface;


class Organizations extends Common
{
    protected $organization;

    public function __construct(OrganizationInterface $organization)
    {
        parent::__construct($organization);

        $this->organization = $organization;
    }

    // Add Position-specific business logic if needed
}
