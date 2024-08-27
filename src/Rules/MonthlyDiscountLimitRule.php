<?php

declare(strict_types=1);

namespace App\Rules;

use App\Constants;
use App\Transaction;

/**
 * Applies the monthly discount limit rule.
 *
 * Design Decisions:
 * - Limits the total discount applied per month to a predefined limit.
 * - Adjusts the transaction's discount if it exceeds the available discount for the month.
 * - Ensures that the discount applied does not push the total discount for the month beyond the limit.
 *
 * Assumptions:
 * - The discount is adjusted if it exceeds the monthly limit.
 * - If a transaction would push the total discount over the limit, the discount is reduced to stay within the limit.
 */

class MonthlyDiscountLimitRule implements RuleInterface
{
    public function apply(Transaction $transaction, array &$context): Transaction
    {
        $monthKey = substr($transaction->getDate(), 0, 7);

        if (!isset($context['monthly_discounts'][$monthKey])) {
            $context['monthly_discounts'][$monthKey] = 0.0;
        }

        $currentMonthDiscount = $context['monthly_discounts'][$monthKey];
        $potentialDiscount = $transaction->getDiscount();

        $availableDiscount = Constants::MONTHLY_DISCOUNT_LIMIT - $currentMonthDiscount;

        if ($potentialDiscount > $availableDiscount) {
            $transaction->setDiscount($availableDiscount);
            $finalPrice = $transaction->getPrice() + ($potentialDiscount - $availableDiscount);
            $transaction->setPrice($finalPrice);
        }

        $context['monthly_discounts'][$monthKey] += $transaction->getDiscount();

        return $transaction;
    }
}
