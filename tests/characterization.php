<?php

declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/TestRunner.php';

$tests = new TestRunner();

function createBooking(
    string $customerType = 'standard',
    string $passType = 'day',
    float $price = 50.0,
    int $quantity = 1,
    ?string $phone = '0600000000'
): Booking {
    $customer = new Customer(1, 'test@example.com', $phone, $customerType);
    $ticket = new Ticket('TEST', 'Ticket test', $price);
    $booking = new Booking(1, $customer, $passType);
    $booking->addItem(new BookingItem($ticket, $quantity));
    return $booking;
}

ob_start();
$service = new BookingService();

$standard = createBooking('standard', 'day', 50.0, 2);
$standardTotal = $service->confirm($standard, 'stripe');
$tests->near(100.0, $standardTotal, 'standard customer keeps initial total');
$tests->same('confirmed', $standard->status, 'booking becomes confirmed');

$vip = createBooking('vip', 'day', 50.0, 2);
$vipTotal = $service->confirm($vip, 'stripe');
$tests->near(90.0, $vipTotal, 'legacy VIP rule gives 10 percent discount');

$threeDays = createBooking('standard', '3days', 60.0, 2);
$threeDaysTotal = $service->confirm($threeDays, 'stripe');
$tests->near(110.0, $threeDaysTotal, 'legacy three day pass discount is 10 euros');

$vip3Days = createBooking('vip', '3days', 50.0, 2);
$vip3DaysTotal = $service->confirm($vip3Days, 'stripe');
$tests->near(80.0, $vip3DaysTotal, 'VIP + 3days combines discounts');

$badEmailBooking = createBooking('standard', 'day', 50.0, 1);
$badEmailBooking->customer->email = 'not_an_email';
try {
    $service->confirm($badEmailBooking, 'stripe');
    $tests->same(true, false, 'Invalid email should throw exception');
} catch (RuntimeException $e) {
    $tests->same('Invalid email', $e->getMessage(), 'Invalid email throws correct exception');
}

$emptyBooking = new Booking(999, new Customer(999, 'test@test.com'));
try {
    $service->confirm($emptyBooking, 'stripe');
    $tests->same(true, false, 'Empty booking should throw exception');
} catch (RuntimeException $e) {
    $tests->same('Empty booking', $e->getMessage(), 'Empty booking throws correct exception');
}

ob_end_clean();
$tests->summary();
