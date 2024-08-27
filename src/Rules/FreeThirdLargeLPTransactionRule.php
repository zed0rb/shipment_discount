<?php

declare(strict_types=1);

namespace App\Rules;

use App\Constants;
use App\Transaction;

class FreeThirdLargeLPTransactionRule implements RuleInterface
{
    public function apply(Transaction $transaction, array &$context): Transaction
    {
        $monthKey = substr($transaction->getDate(), 0, 7);

        if (!isset($context['large_lp_shipments'][$monthKey])) {
            $context['large_lp_shipments'][$monthKey] = 0;
        }

        if ($transaction->getProvider() === 'LP' && $transaction->getSize() === 'L') {
            $context['large_lp_shipments'][$monthKey]++;

            if ($context['large_lp_shipments'][$monthKey] === 3) {
                $transaction->setDiscount(Constants::TRANSACTION_PRICES['LP']['L']);
                $transaction->setPrice(0.0);
            }
        }

        return $transaction;
    }
}
