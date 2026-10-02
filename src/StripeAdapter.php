<?php

declare(strict_types=1);

final class StripeAdapter implements PaymentGateway
{
    public function __construct(
        private readonly StripeClient $client
    ) {
    }

    public function pay(float $amount): string
    {
        return $this->client->charge($amount);
    }
}