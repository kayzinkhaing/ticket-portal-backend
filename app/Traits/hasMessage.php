<?php

namespace App\Traits;

use App\Models\Message;

trait HasMessage
{
    protected $messages = [];

    /**
     * Initialize the trait and load all messages into memory.
     */
    public function initCustomMessages()
    {
        // Load all messages into memory only once.
        if (empty($this->messages)) {
            $this->messages = Message::pluck('message', 'name')->toArray();
        }
    }

    /**
     * Get a message by its key.
     *
     * @param string $key
     * @return string|null
     */
    public function getMessage(string $key): ?string
    {
        return $this->messages[$key] ?? null;
    }

    /**
     * Get all messages.
     *
     * @return array
     */
    public function getAllMessages(): array
    {
        return $this->messages;
    }
}
