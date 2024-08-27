<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\CalculateDiscountsCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class CalculateDiscountsCommandTest extends TestCase
{
    private const string INPUT_FILE = 'input.txt';

    public function testCalculateDiscountsCommand(): void
    {
        $application = new Application();
        $application->add(new CalculateDiscountsCommand());

        $command = $application->find('app:calculate-discounts');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['inputFile' => self::INPUT_FILE]);

        // Normalize the line endings and trim any extra whitespace
        $expectedOutput = str_replace("\r\n", "\n", <<<'EOT'
2015-02-01 S MR 1.50 0.50
2015-02-02 S MR 1.50 0.50
2015-02-03 L LP 6.90 -
2015-02-05 S LP 1.50 -
2015-02-06 S MR 1.50 0.50
2015-02-06 L LP 6.90 -
2015-02-07 L MR 4.00 -
2015-02-08 M MR 3.00 -
2015-02-09 L LP 0.00 6.90
2015-02-10 L LP 6.90 -
2015-02-10 S MR 1.50 0.50
2015-02-10 S MR 1.50 0.50
2015-02-11 L LP 6.90 -
2015-02-12 M MR 3.00 -
2015-02-13 M LP 4.90 -
2015-02-15 S MR 1.50 0.50
2015-02-17 L LP 6.90 -
2015-02-17 S MR 1.90 0.10
2015-02-24 L LP 6.90 -
2015-02-29 CUSPS Ignored
2015-03-01 S MR 1.50 0.50
EOT
        );

        $actualOutput = str_replace("\r\n", "\n", $commandTester->getDisplay());

        // Remove trailing whitespace to avoid discrepancies
        $expectedOutput = rtrim($expectedOutput);
        $actualOutput = rtrim($actualOutput);

        $this->assertEquals($expectedOutput, $actualOutput);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Create a temporary input file for testing
        $filesystem = new Filesystem();
        $filesystem->dumpFile(self::INPUT_FILE, $this->getSampleInput());
    }

    private function getSampleInput(): string
    {
        return <<<'EOT'
2015-02-01 S MR
2015-02-02 S MR
2015-02-03 L LP
2015-02-05 S LP
2015-02-06 S MR
2015-02-06 L LP
2015-02-07 L MR
2015-02-08 M MR
2015-02-09 L LP
2015-02-10 L LP
2015-02-10 S MR
2015-02-10 S MR
2015-02-11 L LP
2015-02-12 M MR
2015-02-13 M LP
2015-02-15 S MR
2015-02-17 L LP
2015-02-17 S MR
2015-02-24 L LP
2015-02-29 CUSPS
2015-03-01 S MR
EOT;
    }
}
