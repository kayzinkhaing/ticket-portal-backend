<?php

namespace App\Helper;

use App\Models\Message;
use Illuminate\Support\Facades\Cache;

class CustomMessages
{
    protected array $messages;

    public function __construct()
    {
        $this->messages = Cache::remember('custom_ui_messages', 86400, function () {
            return Message::pluck('message', 'name')->toArray();
        });
    }

    /**
     * Get a message by key
     */
    public function getMessage(string $key): string
    {

        return $this->messages[$key] ?? "Message [$key] not defined.";
    }

    /**
     * Get all messages
     */
    public function all(): array
    {
        return $this->messages;
    }
}
