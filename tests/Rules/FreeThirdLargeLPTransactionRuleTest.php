<?php

declare(strict_types=1);

namespace App\Tests\Rules;

use App\Constants;
use App\Rules\FreeThirdLargeLPTransactionRule;
use App\Transaction;
use PHPUnit\Framework\TestCase;

class FreeThirdLargeLPTransactionRuleTest extends TestCase
{
    private FreeThirdLargeLPTransactionRule $rule;

    public function testApplyThirdLargeLPShipment(): void
    {
        $context = ['large_lp_shipments' => ['2024-01' => 2]];
        $transaction = new Transaction('2024-01-03', 'L', 'LP');

        $this->rule->apply($transaction, $context);

        $this->assertEquals(Constants::TRANSACTION_PRICES['LP']['L'], $transaction->getDiscount());
    }

    public function testDoNotApplyForFirstLargeLPShipment(): void
    {

        $context = ['large_lp_shipments' => ['2024-01' => 0]];
        $transaction = new Transaction('2024-01-01', 'L', 'LP');

        $this->rule->apply($transaction, $context);

        $this->assertEquals(0.0, $transaction->getDiscount());
    }

    protected function setUp(): void
    {
        $this->rule = new FreeThirdLargeLPTransactionRule();
    }
}