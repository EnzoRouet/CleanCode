<?php

class SMSConfirmationObserver implements BookingObserver
{
    public function update(Booking $booking): void
    {
        if ($booking->customer->phone !== null)
        {
            $phoneService = new SmsClient();
            $phoneService->send($booking->customer->phone, "booking {$booking->id} confirmed" . PHP_EOL);
        }
    }
}