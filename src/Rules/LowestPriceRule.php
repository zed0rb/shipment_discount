<?php

declare(strict_types=1);

namespace App\Rules;

use App\Constants;
use App\Transaction;

class LowestPriceRule implements RuleInterface
{
    public function apply(Transaction $transaction, array &$context): Transaction
    {
        if ($transaction->getSize() === 'S') {
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
