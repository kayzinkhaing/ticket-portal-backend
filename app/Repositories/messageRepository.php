<?php

namespace App\Repositories;

use App\Contracts\MessageInterface;

class MessageRepository extends BaseRepository implements MessageInterface
{
    public function __construct()
    {
        parent::__construct(class_basename("Message"));
    }
    public function getMessageByKey(string $key): string
    {
        $message = $this->model->findByName('name', $key)->first();

        if ($message) {
            return $message->message;
        }

        // Fallback if the message is not found, could be a default message
        return "Message not found for {$key}";
    }
}
