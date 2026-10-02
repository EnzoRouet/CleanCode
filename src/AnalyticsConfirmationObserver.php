<?php

class AnalyticsConfirmationObserver implements BookingObserver
{
    public function update(Booking $booking): void
    {
        $analyticsService = new AnalyticsClient();
        
        $data = [
            'booking_id' => $booking->id,
            'customer_type' => $booking->customer->type,
            'pass_type' => $booking->passType
        ];
        
        $analyticsService->track('booking_confirmed', $data);
    }
}