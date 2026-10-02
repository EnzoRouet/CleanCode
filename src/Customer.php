<?php

declare(strict_types=1);

final class Customer
{
    public string $email;

    public function __construct(
        public int $id,
        string $email,
        public ?string $phone = null,
        public string $type = 'standard'
    ) {
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Invalid email');
        }

        $this->email = $email;
    }
}
