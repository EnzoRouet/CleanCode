<?php
declare(strict_types=1);

interface PaymentGateway
{
    /**
     * @param float $amount Montant à payer
     * @return string ID de la transaction
     * @throws RuntimeException Si le paiement échoue
     */
    public function pay(float $amount): string;
}
