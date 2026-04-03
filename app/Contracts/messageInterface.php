<?php

namespace App\Contracts;

interface messageInterface extends baseInterface
{
    public function getMessageByKey(string $key): string;
}
