<?php

declare(strict_types=1);

namespace App\Command;

use App\Constants;
use App\Rules\FreeThirdLargeLPTransactionRule;
use App\Rules\LowestPriceRule;
use App\Rules\MonthlyDiscountLimitRule;
use App\Transaction;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:calculate-discounts',
    description: 'Calculates transactions discounts from a file'
)]
class CalculateDiscountsCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('inputFile', InputArgument::OPTIONAL, 'Path to the input file', 'input.txt');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFile = $input->getArgument('inputFile');
        $context = [];

        if (!file_exists($inputFile)) {
            $output->writeln('<error>Input file does not exist.</error>');
            return Command::FAILURE;
        }

        $lines = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $rules = $this->getRules();

        foreach ($lines as $line) {
            $transaction = $this->parseLine($line);
            if ($transaction === null) {
                $output->writeln($line . ' Ignored');
                continue;
            }

            foreach ($rules as $rule) {
                $transaction = $rule->apply($transaction, $context);
            }

            $output->writeln(sprintf(
                "%s %s %s %.2f %s",
                $transaction->getDate(),
                $transaction->getSize(),
                $transaction->getProvider(),
                $transaction->getPrice(),
                $transaction->getDiscount() > 0 ? sprintf('%.2f', $transaction->getDiscount()) : '-'
            ));
        }

        return Command::SUCCESS;
    }

    private function parseLine(string $line): ?Transaction
    {
        $parts = explode(' ', $line);

        if (count($parts) !== 3) {
            return null;
        }

        [$date, $size, $provider] = $parts;

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !isset(Constants::TRANSACTION_PRICES[$provider][$size])) {
            return null;
        }

        return new Transaction($date, $size, $provider);
    }

    private function getRules(): array
    {
        return [
            new LowestPriceRule(),
            new FreeThirdLargeLPTransactionRule(),
            new MonthlyDiscountLimitRule(),
        ];
    }
}
