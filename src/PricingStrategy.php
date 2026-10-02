<?php

declare(strict_types=1);

interface PricingStrategy
{
    public function totalPrice(Booking $booking): float;
}