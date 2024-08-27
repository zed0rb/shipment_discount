<?php

declare(strict_types=1);

namespace App\Rules;

use App\Constants;
use App\Transaction;

/**
 * Applies a free shipment rule for the third large LP transaction in a month.
 *
 * Design Decisions:
 * - For each month, count the number of large 'LP' transactions.
 * - Apply a free shipment discount (price set to 0) to the third large 'LP' transaction in that month.
 * - Increase the discount by the price of a large 'LP' transaction when the third transaction is encountered.
 *
 * Assumptions:
 * - The discount for the third large 'LP' transaction is equal to the price of the transaction.
 * - The rule only applies if there are exactly three large 'LP' transactions in the month.
 * - The price of the transaction is reset to 0.0 when the free shipment is applied.
 */

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
