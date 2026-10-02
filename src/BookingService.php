<?php

declare(strict_types=1);

final class BookingService
{
    public function confirm(Booking $booking, PaymentGateway $paymentGateway): float
    {
        if (count($booking->items) === 0) {
            throw new RuntimeException('Empty booking');
        }

        if (!filter_var($booking->customer->email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Invalid email');
        }

        $pricingStrategy = new PricingCalculator();
        $total = $pricingStrategy->totalPrice($booking);

        $transactionId = $paymentGateway->pay($total);
        echo 'Payment successful, transaction ID: ' . $transactionId . PHP_EOL;
        
        $booking->status = 'confirmed';

        echo "SQL INSERT booking={$booking->id} total={$total} status={$booking->status}" . PHP_EOL;

        $emailService = new EmailService();
        $emailService->sendConfirmation($booking->customer->email, $booking->id);

        return $total;
    }
}


// Remise VIP, Pass 3 jours, verification payment, verification quantité items, email, enregistrement SQL, calcul du prix total, garde fous email, verification booking pas vide