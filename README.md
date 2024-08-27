# Transaction Discount Calculator

## Overview

This project calculates discounts for transactions based on various rules. It includes functionality to:

- Apply the lowest price rule.
- Handle free shipments for certain transactions.
- Enforce monthly discount limits.

## Getting Started

To get started with the application, follow these steps:

### Prerequisites

Ensure you have the following installed:

- PHP (version 8.0 or higher)
- Composer (dependency manager for PHP)
- Symfony Console Component
- PHPUnit (for running tests)

### Installation

1. **Install Dependencies**

   Run Composer to install the required PHP dependencies. Execute the following command in your terminal:

   `composer install`

2. **Execute the Command**

   To calculate discounts, use the Symfony command. Run the following command in your terminal:

   `php bin/console app:calculate-discounts`

   If you need to specify a different input file, add the `inputFile` argument:

   `php bin/console app:calculate-discounts input.txt`

   The output will be displayed in the console.

## Running Tests

1. **Run All Tests**

   To execute the test suite and ensure everything is working correctly, run the following command in your terminal:

   `./vendor/bin/phpunit`

   This command will run all the tests located in the `tests` directory.


## Documentation

For more detailed information on the rules and implementation, refer to the code comments and design documentation included in the project.


