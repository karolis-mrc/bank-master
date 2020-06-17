<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Classes;

class Commission
{
    public $cash_in_fee;
    public $cash_in_fee_max;
    public $cash_out_limit;
    public $cash_out_count_limit;
    public $cash_out_fee;
    public $cash_out_fee_min;

    public function __construct()
    {
        $this->cash_in_fee = '0.0003';
        $this->cash_in_fee_max = '5.00';
        $this->cash_out_limit = '1000';
        $this->cash_out_count_limit = 3;
        $this->cash_out_fee = '0.003';
        $this->cash_out_fee_min = '0.5';
    }

    public function setCashInFee(string $cash_in_fee)
    {
        if (!is_numeric($cash_in_fee)) {
            throw new \InvalidArgumentException('Cash in fee should be numeric.');
        } elseif (intval($cash_in_fee) < 0) {
            throw new \InvalidArgumentException('Cash in fee should be zero or greater.');
        }

        $this->cash_in_fee = $cash_in_fee;
    }

    public function getCashInFee(): string
    {
        return $this->cash_in_fee;
    }

    public function setCashInFeeMax(string $cash_in_fee_max)
    {
        if (!is_numeric($cash_in_fee_max)) {
            throw new \InvalidArgumentException('Maximum of cash in fee should be numeric.');
        } elseif (intval($cash_in_fee_max) < 0) {
            throw new \InvalidArgumentException('Maximum of cash in fee should be zero or greater.');
        }

        $this->cash_in_fee_max = $cash_in_fee_max;
    }

    public function getCashInFeeMax(): string
    {
        return $this->cash_in_fee_max;
    }

    public function setCashOutLimit(string $cash_out_limit)
    {
        if (!is_numeric($cash_out_limit)) {
            throw new \InvalidArgumentException('The limit of cash out money amount should be numeric.');
        } elseif (intval($cash_out_limit) < 0) {
            throw new \InvalidArgumentException('The limit of cash out money amount should be zero or greater.');
        }

        $this->cash_out_limit = $cash_out_limit;
    }

    public function getCashOutLimit(): string
    {
        return $this->cash_out_limit;
    }

    public function setCashOutCountLimit(int $cash_out_count_limit)
    {
        if ($cash_out_count_limit < 0) {
            throw new \InvalidArgumentException('The limit of cash out operations count should be zero or greater.');
        }

        $this->cash_out_count_limit = $cash_out_count_limit;
    }

    public function getCashOutCountLimit(): int
    {
        return $this->cash_out_count_limit;
    }

    public function setCashOutFee(string $cash_out_fee)
    {
        if (!is_numeric($cash_out_fee)) {
            throw new \InvalidArgumentException('Cash out fee should be numeric.');
        } elseif (intval($cash_out_fee) < 0) {
            throw new \InvalidArgumentException('Cash out fee should be zero or greater.');
        }

        $this->cash_out_fee = $cash_out_fee;
    }

    public function getCashOutFee(): string
    {
        return $this->cash_out_fee;
    }

    public function setCashOutFeeMin(string $cash_out_fee_min)
    {
        if (!is_numeric($cash_out_fee_min)) {
            throw new \InvalidArgumentException('The minimal cash out fee should be numeric.');
        } elseif (intval($cash_out_fee_min) < 0) {
            throw new \InvalidArgumentException('The minimal cash out fee should be zero or greater.');
        }

        $this->cash_out_fee_min = $cash_out_fee_min;
    }

    public function getCashOutFeeMin(): string
    {
        return $this->cash_out_fee_min;
    }
}
