<?php

declare(strict_types=1);

namespace App\Rules;

use App\Constants;
use App\Transaction;

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
            $transaction->setPrice($transaction->getPrice() + ($potentialDiscount - $availableDiscount));
        }

        $context['monthly_discounts'][$monthKey] += $transaction->getDiscount();

        return $transaction;
    }
}
