<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CommonTables;

class CommonTableController extends Controller
{
    protected $commonTables;

    public function __construct(CommonTables $commonTables)
    {
        $this->commonTables = $commonTables;

        parent::__construct(
            $this->commonTables
        );
    }
}
