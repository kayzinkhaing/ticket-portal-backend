<?php

namespace App\Repositories;

use App\Contracts\orderInterface;

class orderRepository extends baseRepository implements orderInterface
{
    public function __construct()
    {
        parent::__construct(class_basename("Order"));
    }
    public function getByOrderId($orderId)
    {
        return [];
    }
}
