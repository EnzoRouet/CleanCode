<?php

class EmailConfirmationObserver implements BookingObserver
{
    public function update(Booking $booking): void
    {
        $emailService = new EmailService();
        $emailService->sendConfirmation($booking->customer->email, $booking->id);
    }
}