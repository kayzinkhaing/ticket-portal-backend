<?php
// app/Contracts/TicketInterface.php
namespace App\Contracts;

use App\Contracts\BaseInterface;

interface TicketInterface extends BaseInterface
{
    // Ticket-specific methods can be added here
    // For example:
    public function getByRoleAndStatus(string $role, ?string $status = null);
    public function getStatusCounts(string $role): array;
    // public function addSlaStatus($ticket);
    public function getTicketsByOrganization(array $orgIds);
}
