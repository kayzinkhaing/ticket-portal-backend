<?php

namespace App\Services;

use App\Repositories\messageRepository;

class messages extends Common
{
    public $mesgRepo;
    public function __construct(messageRepository $messageRepository)
    {
        parent::__construct($messageRepository);
        $this->mesgRepo = $messageRepository;
    }
}
