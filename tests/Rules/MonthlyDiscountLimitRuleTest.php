<?php

declare(strict_types=1);

namespace App\Tests\Rules;

use App\Rules\MonthlyDiscountLimitRule;
use App\Transaction;
use PHPUnit\Framework\TestCase;

class MonthlyDiscountLimitRuleTest extends TestCase
{
    private MonthlyDiscountLimitRule $monthlyDiscountLimitRule;
    private array $context;

    public function testApplyDiscountWithinLimit(): void
    {
        $transaction = new Transaction('2024-08-09', 'S', 'MR');
        $transaction->setDiscount(0.50);
        $transaction->setPrice(1.50);

        // Apply the rule
        $this->monthlyDiscountLimitRule->apply($transaction, $this->context);

        // Assert that the discount is applied correctly
        $this->assertEquals(0.50, $transaction->getDiscount());
        $this->assertEquals(1.50, $transaction->getPrice());
    }

    public function testCumulativeDiscountsWithinLimit(): void
    {
        // First transaction
        $transaction1 = new Transaction('2024-08-01', 'S', 'LP');
        $transaction1->setDiscount(9.90);
        $transaction1->setPrice(0.00);

        $this->monthlyDiscountLimitRule->apply($transaction1, $this->context);

        // Assert that the first discount is applied
        $this->assertEquals(9.90, $transaction1->getDiscount());
        $this->assertEquals(0.00, $transaction1->getPrice());

        // Second transaction
        $transaction2 = new Transaction('2024-08-15', 'S', 'MR');
        $transaction2->setDiscount(0.50);
        $transaction2->setPrice(1.50);

        $this->monthlyDiscountLimitRule->apply($transaction2, $this->context);

        // Assert that the second discount is adjusted not to exceed the monthly limit
        $this->assertEqualsWithDelta(0.10, $transaction2->getDiscount(), 0.01);
        $this->assertEqualsWithDelta(1.90, $transaction2->getPrice(), 0.01);
    }

    protected function setUp(): void
    {
        $this->monthlyDiscountLimitRule = new MonthlyDiscountLimitRule();
        $this->context = [
            'monthly_discounts' => [],
        ];
    }
}
