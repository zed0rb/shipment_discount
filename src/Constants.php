<?php

declare(strict_types=1);

namespace App;

class Constants
{
    public const array TRANSACTION_PRICES = [
        'LP' => [
            'S' => 1.50,
            'M' => 4.90,
            'L' => 6.90,
        ],
        'MR' => [
            'S' => 2.00,
            'M' => 3.00,
            'L' => 4.00,
        ],
    ];

    public const float MONTHLY_DISCOUNT_LIMIT = 10.00;
}