<?php

namespace App\Contracts;


interface productInterface extends baseInterface
{
    public function getByProductId(int $productId);
}
