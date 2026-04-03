<?php

namespace App\Contracts;

interface orderInterface extends baseInterface
{
    function getByOrderId($orderId);
}
