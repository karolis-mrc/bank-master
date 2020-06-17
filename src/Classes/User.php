<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Classes;

use Bank\CommissionTask\Service\Math;

class User extends Controller
{
    private $id;
    private $user_type;
    private $cash_out_count;
    private $cash_per_week;

    public function __construct(int $id, string $user_type)
    {
        $this->id = $id;
        $this->user_type = $user_type;
        $this->cash_out_count = 0;
        $this->cash_per_week = '0.00';
    }

    public function setId(int $id)
    {
        if ($id < 0) {
            throw new \InvalidArgumentException('ID should be zero and greater.');
        }

        $this->id = $id;
    }

    public function setUserType(string $user_type)
    {
        $user_types = ['legal', 'natural'];

        if (!in_array($user_type, $user_types, true)) {
            throw new \InvalidArgumentException("User type should be either 'legal' or 'natural'.");
        }

        $this->user_type = $user_type;
    }

    public function isLegal(): bool
    {
        return $this->user_type === 'legal';
    }

    public function setCashOutCount(int $cash_out_count)
    {
        if ($cash_out_count < 0) {
            throw new \InvalidArgumentException('Cash out count should be zero or greater.');
        }

        $this->cash_out_count = $cash_out_count;
    }

    public function getCashOutCount(): int
    {
        return $this->cash_out_count;
    }

    public function setCashPerWeek(string $cash_per_week)
    {
        if (!is_numeric($cash_per_week)) {
            throw new \InvalidArgumentException('Cash per week should be numeric.');
        } elseif (intval($cash_per_week) < 0) {
            throw new \InvalidArgumentException('Cash per week should be zero or greater.');
        }

        $this->cash_per_week = $cash_per_week;
    }

    public function getCashPerWeek(): string
    {
        return $this->cash_per_week;
    }

    public function setCurrency(string $currency)
    {
        $currency_types = ['EUR', 'USD', 'JPY'];

        if (!in_array($currency, $currency_types, true)) {
            throw new \InvalidArgumentException("Currency type should be either 'EUR', 'USD' or 'JPY'.");
        }

        $this->currency = $currency;
    }

    public function addToCashOutCount()
    {
        ++$this->cash_out_count;
    }

    public function addToCashPerWeek(string $amount)
    {
        $math = new Math(4);
        $this->cash_per_week = $math->add($this->cash_per_week, $amount);
    }
}
