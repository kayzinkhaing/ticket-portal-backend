<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    public function charge(float $amount, array $paymentDetails): array;
    public function refund(string $transactionId, float $amount): array;
    public function getTransactionStatus(string $transactionId): array;
}
