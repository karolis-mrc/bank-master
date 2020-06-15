<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Classes;

use Bank\CommissionTask\Service\Math;

class Converter
{
    private $usd = '1.1497';
    private $jpy = '129.53';
    private $math;

    public function __construct()
    {
        $this->math = new Math(4);
    }

    public function usdToEur(string $amount): string
    {
        return $this->math->divide($amount, $this->usd);
    }

    public function jpyToEur(string $amount): string
    {
        return $this->math->divide($amount, $this->jpy);
    }

    public function eurToUsd(string $amount): string
    {
        return $this->math->multiply($amount, $this->usd);
    }

    public function eurToJpy(string $amount): string
    {
        return $this->math->multiply($amount, $this->jpy);
    }
}
