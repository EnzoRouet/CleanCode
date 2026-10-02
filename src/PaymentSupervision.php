<?php
declare(strict_types=1);

final class PaymentSupervision implements PaymentGateway
{
    public function __construct(private readonly PaymentGateway $paymentGateway)
    {}

    public function pay(float $amount): string
    {
        echo "[SUPERVISION] Tentative de paiement demandée pour un montant de : {$amount}" . PHP_EOL; // Journalisation du montant a payer

        $startTime = microtime(true); // Mesure du Temps

        try {
            // Déléguer le paiement réel au composant interne (StripeAdapter ou PayFastAdapter)
            $transactionId = $this->paymentGateway->pay($amount);
            
            // Mesurer le temps écoulé et journaliser le succès
            $duration = microtime(true) - $startTime;
            echo sprintf("[SUPERVISION] Paiement réussi en %.4f secondes. Transaction : %s" . PHP_EOL, $duration, $transactionId);

            return $transactionId;

        } catch (RuntimeException $e) {
            // Journaliser l'échec du paiement
            $duration = microtime(true) - $startTime;
            echo sprintf("[SUPERVISION] Échec du paiement après %.4f secondes. Raison : %s" . PHP_EOL, $duration, $e->getMessage());
            throw $e; // Propager l'exception pour que le service de réservation puisse la gérer
        }
    }
}