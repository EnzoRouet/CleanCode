<?php

interface BookingObserver
{
    public function update(Booking $booking): void;
}