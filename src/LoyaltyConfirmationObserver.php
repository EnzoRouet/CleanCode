<?php

class LoyaltyConfirmationObserver implements BookingObserver
{
    public function update(Booking $booking): void
    {
        $loyaltyService = new LoyaltyService();
        $loyaltyService->addPoints($booking->customer->id, $booking->points);
    }
}