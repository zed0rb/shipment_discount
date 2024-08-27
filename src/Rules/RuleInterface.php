<?php

declare(strict_types=1);

namespace App\Rules;

use App\Transaction;

interface RuleInterface
{
    public function apply(Transaction $transaction, array &$context): Transaction;
}