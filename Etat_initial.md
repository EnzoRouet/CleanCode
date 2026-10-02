php index.php : Output :

PAYMENT stripe_143.82
SQL INSERT booking=1001 total=143.82 status=confirmed
EMAIL lea@example.com: booking 1001 confirmed
TOTAL FINAL: 143.82

php tests/characterization.php : Output :

Passed: 4, Failed: 0

Output Final :

php index.php : Output :

Payment successful, transaction ID: stripe_143.82
SQL INSERT booking=1001 total=143.82 status=confirmed
EMAIL lea@example.com: booking 1001 confirmed
LOYALTY customer=42 points=10
ANALYTICS booking_confirmed {"booking_id":1001,"customer_type":"vip","pass_type":"day"}
SMS 0612345678: booking 1001 confirmed

TOTAL FINAL: 143.82

php tests/characterization.php : Output :
