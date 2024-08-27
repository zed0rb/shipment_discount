<?php

declare(strict_types=1);

namespace App;

class Transaction
{

    private float $price = 0.0;
    private float $discount = 0.0;

    public function __construct(
        private readonly string $date,
        private readonly string $size,
        private readonly string $provider
    )
    {
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = $discount;
    }
}
