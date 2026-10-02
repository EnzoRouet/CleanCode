<?php
declare(strict_types=1);

final class PayFastAdapter implements PaymentGateway
{
    public function __construct(private readonly PayFastSdk $sdk){}

    public function pay(float $amount): string
    {
        $payload = [
            'reference' => uniqid('payfast_', true),
            'amount_cents' => (int) round($amount * 100),
            'currency' => 'EUR',
       ];
       $response = $this->sdk->executePayment($payload);

       if (!$response['success']) {
              throw new RuntimeException('Le paiement a échoué.');
       }
       return $response['transaction_id'];
    }
}