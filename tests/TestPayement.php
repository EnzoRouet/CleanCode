<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

// TEST 1 : Paiement Stripe
try {
    $stripeAdapter = new StripeAdapter(new StripeClient());
    $transactionId = $stripeAdapter->pay(50.0);
    
    if (strpos($transactionId, 'stripe_') === 0) {
        echo "TEST 1 (Stripe) : Succès ! Transaction ID : $transactionId\n";
    } else {
        echo "TEST 1 (Stripe) : Échec. Mauvais format d'ID.\n";
    }
} catch (Throwable $e) {
    echo "TEST 1 (Stripe) : Échec inattendu ({$e->getMessage()})\n";
}

// TEST 2 : Paiement PayFast
try {
    $payFastAdapter = new PayFastAdapter(new PayFastSdk());
    $transactionId = $payFastAdapter->pay(75.50);
    
    if (strpos($transactionId, 'payfast_') === 0) {
        echo "TEST 2 (PayFast) : Succès ! Transaction ID : $transactionId\n";
    } else {
        echo "TEST 2 (PayFast) : Échec. Mauvais format d'ID.\n";
    }
} catch (Throwable $e) {
    echo "TEST 2 (PayFast) : Échec inattendu ({$e->getMessage()})\n";
}

// TEST 3 : PayFast avec montant invalide
try {
    $payFastAdapter = new PayFastAdapter(new PayFastSdk());
    $payFastAdapter->pay(-10.0); // Devrait déclencher une RuntimeException
    
    echo "TEST 3 (Erreur PayFast) : Échec. Le code aurait dû planter mais a continué.\n";
} catch (RuntimeException $e) {

    echo "TEST 3 : Le paiement a été bloqué : '{$e->getMessage()}'\n";
}
