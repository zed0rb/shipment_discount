<?php

declare(strict_types=1);

namespace App\Rules;

use App\Constants;
use App\Transaction;

/**
 * Applies the lowest price rule for transactions.
 *
 * Design Decisions:
 * - For 'S' size transactions, the price is set to the lowest available price between LP and MR providers.
 * - For non-'S' sizes, the standard price for the provider is applied.
 * - Discount is calculated as the difference between the current price and the lowest price.
 *
 * Assumptions:
 * - The lowest price calculation only applies to 'S' size packages.
 * - The discount can only be applied if it does not exceed the available discount for the month.
 */

class LowestPriceRule implements RuleInterface
{
    public function apply(Transaction $transaction, array &$context): Transaction
    {
        if ($transaction->getSize() === 'S') {

            /*  $lowestPrice = min(
                Constants::TRANSACTION_PRICES['LP']['S'],
                Constants::TRANSACTION_PRICES['MR']['S']
            ); */

            $lowestPrice = PHP_FLOAT_MAX;

            foreach (Constants::TRANSACTION_PRICES as $providerPrices) {
                if (isset($providerPrices['S'])) {
                    $lowestPrice = min($lowestPrice, $providerPrices['S']);
                }
            }

            $currentPrice = Constants::TRANSACTION_PRICES[$transaction->getProvider()]['S'];
            $transaction->setDiscount($currentPrice - $lowestPrice);
            $transaction->setPrice($lowestPrice);
        } else {
            $transaction->setPrice(Constants::TRANSACTION_PRICES[$transaction->getProvider()][$transaction->getSize()]);
        }

        return $transaction;
    }
}
