<?php

declare(strict_types=1);

namespace App\Tests\Rules;

use App\Constants;
use App\Rules\LowestPriceRule;
use App\Transaction;
use PHPUnit\Framework\TestCase;

class LowestPriceRuleTest extends TestCase
{
    private LowestPriceRule $lowestPriceRule;
    private array $context;

    public function testApplyLowestPriceForSmallPackage(): void
    {
        $transaction = new Transaction('2024-08-09', 'S', 'MR');

        $this->lowestPriceRule->apply($transaction, $this->context);

        // Get the expected price
        $expectedPrice = Constants::TRANSACTION_PRICES['LP']['S']; // Lowest price for 'S' is from 'LP'

        // Assert that the transaction price is set to the lowest price
        $this->assertEquals($expectedPrice, $transaction->getPrice());
    }

    public function testNotChangePriceForMediumAndLargeTransactions(): void
    {
        // Medium LP transaction
        $transactionM = new Transaction('2024-08-09', 'M', 'LP');

        $this->lowestPriceRule->apply($transactionM, $this->context);

        // Assert that there is no rule applied for medium size
        $this->assertEquals(Constants::TRANSACTION_PRICES['LP']['M'], $transactionM->getPrice());


        // Large MR transaction
        $transactionL = new Transaction('2024-08-09', 'L', 'MR');

        $this->lowestPriceRule->apply($transactionL, $this->context);

        // Assert that there is no rule applied for large size
        $this->assertEquals(Constants::TRANSACTION_PRICES['MR']['L'], $transactionL->getPrice());
    }

    protected function setUp(): void
    {
        $this->lowestPriceRule = new LowestPriceRule();
        $this->context = [
            'monthly_discounts' => [],
        ];
    }
}