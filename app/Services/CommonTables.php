<?php
// app/Services/CommonTables.php
namespace App\Services;

use App\Contracts\CommonTableInterface;

class CommonTables extends Common
{
    protected $commonTable;

    public function __construct(CommonTableInterface $commonTable)
    {
        parent::__construct($commonTable);

        $this->commonTable = $commonTable;
    }

    // Add CommonTable-specific business logic if needed
}
