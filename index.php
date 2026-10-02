<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

$customer = new Customer(
    id: 42,
    email: 'lea@example.com',
    phone: '0612345678',
    type: 'vip'
);

$dayTicket = new Ticket(
    code: 'DAY-1',
    label: 'Pass Jour 1',
    price: 79.90
);

$booking = new Booking(
    id: 1001,
    customer: $customer,
    passType: 'day'
);

$booking->addItem(new BookingItem($dayTicket, 2));

$service = new BookingService();

$service->addObservers(new EmailConfirmationObserver());
$service->addObservers(new LoyaltyConfirmationObserver());
$service->addObservers(new AnalyticsConfirmationObserver());
$service->addObservers(new SMSConfirmationObserver());


$stripeClient = new StripeClient();
$stripeAdapter = new StripeAdapter($stripeClient);

$supervisedPayment = new PaymentSupervision($stripeAdapter);

$total = $service->confirm($booking, $supervisedPayment);

echo 'TOTAL FINAL: ' . number_format($total, 2, '.', '') . PHP_EOL;
