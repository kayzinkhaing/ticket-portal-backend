<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\configFiles;
use App\Services\messages;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $message;
    public function __construct(messages $message)
    {
        parent::__construct($message, $this->FALSE, null);
        $this->message = $message;
    }
}
