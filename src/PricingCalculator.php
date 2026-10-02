<?php

class PricingCalculator implements PricingStrategy
{
    private const FIVE_PERCENT_VIP_DISCOUNT_PRICE = 100.00;
    private const TEN_PERCENT_VIP_DISCOUNT_PRICE = 300.00;
    private const FIVE_PERCENT_VIP_DISCOUNT = 0.95;
    private const TEN_PERCENT_VIP_DISCOUNT = 0.90;
    private const FIFTEEN_PERCENT_VIP_DISCOUNT = 0.85;
    private const THREE_DAY_DISCOUNT = 20.00;

    private function basicPrice(int $quantity, float $unitPrice): float
    {
        return $quantity * $unitPrice;
    }

    private function vipDiscount(float $initPrice): float
    {
        if ($initPrice < self::FIVE_PERCENT_VIP_DISCOUNT_PRICE) { return $initPrice * self::FIVE_PERCENT_VIP_DISCOUNT; }

        if ($initPrice < self::TEN_PERCENT_VIP_DISCOUNT_PRICE) { return $initPrice * self::TEN_PERCENT_VIP_DISCOUNT; }

        return $initPrice * self::FIFTEEN_PERCENT_VIP_DISCOUNT;
    }

    private function threeDayPassDiscount(float $initPrice): float
    {
        return $initPrice - self::THREE_DAY_DISCOUNT;
    }

    public function totalPrice(Booking $booking): float
    {
        $amount = 0.00;

        foreach ($booking->items as $item) {
            $amount += $this->basicPrice($item->quantity, $item->ticket->price);
        }

        if ($booking->customer->type === 'vip')
        {
            $amount = $this->vipDiscount($amount);
        }

        if ($booking->passType === '3days')
        {
            $amount = $this->threeDayPassDiscount($amount);
        }

        if ($amount < 0) {
            $amount = 0.00;
        }

        return $amount;
    }
}