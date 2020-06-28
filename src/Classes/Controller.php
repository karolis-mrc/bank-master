<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Classes;

use Bank\CommissionTask\Service\Math;

class Controller
{
    private $last_operation_week;
    private $operation_week;

    public function __construct()
    {
        $this->setLastOperationWeek('0');
        $this->setOperationWeek('0');
    }

    public function getOperationWeekNumber(string $date): string
    {
        /**
         *  @ This method converts date string into format of year and week ex: '2020-01-01' -> '202001'.
         */
        $ddate = $date;
        $date = new \DateTime($ddate);
        $operation_week = $date->format('oW');

        return $operation_week;
    }

    public function setOperationWeek(string $operation_week): void
    {
        $this->operation_week = $operation_week;
    }

    public function getOperationWeek(): string
    {
        return $this->operation_week;
    }

    public function setLastOperationWeek(string $operation_week): void
    {
        $this->last_operation_week = $operation_week;
    }

    public function getLastOperationWeek(): string
    {
        return $this->last_operation_week;
    }

    public function operationWeeksDiffer(string $date): bool
    {
        /*
         *  @ This method is comparing the date of present operation and date of last operation.
         */
        if ($date < $this->last_operation_week) {
            throw new \InvalidArgumentException();
        }

        if ($this->operation_week !== $this->getOperationWeekNumber($date)) {
            return true;
        } else {
            return false;
        }
    }

    public function dateCheck(string $date): bool
    {
        /*
         *  @ This method is checking if date's year, month and day are separated specifically with '-'.
         *  @ Also it is checking if format of the date is correct.
         */
        if (strpos($date, '/') !== false) {
            return false;
        } elseif (!preg_match('/\d{4}-\d{2}-\d{2}/', $date)) {
            return false;
        } elseif (strtotime($date)) {
            return true;
        } else {
            return false;
        }
    }

    public function operate(string $operation_type, int $amount, string $currency, string $date): string
    {
         /**
         *  @ Operation type is whether 'cash_in' or 'cash_out'.
         *  @ Amount is the cash number that is used in operation.
         *  @ Currency is the currency of operation.
         *  @ Date is the present operation's date.
         */
        $operations_array = ['cash_in', 'cash_out'];
        if (!in_array($operation_type, $operations_array, true)) {
            throw new \InvalidArgumentException("Wrong type of operation, should be either 'cash_in' or 'cash_out'.");
        }

        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount of money should be numeric, more than zero and without any letters');
        }

        if (!$this->dateCheck($date)) {
            throw new \InvalidArgumentException('Wrong date input or format');
        }
        
        switch ($operation_type) {
            case 'cash_in':
                $commission_fee = (float) $this->countCashInCommission($amount, $currency);
                break;

            case 'cash_out':
                $commission_fee = (float) $this->countCashOutCommission($amount, $currency, $date);
                break;
            
            default:
                throw new \InvalidArgumentException("Operation type should be 'cash_in' or 'cash_out'.");
                break;
        }

        if ($currency === 'JPY') {
            $commission_fee = (string) ceil($commission_fee);
        } else {
            $commission_fee =  number_format(ceil($commission_fee * 100) / 100, 2, '.', '');
        }

        return $commission_fee;
    }

    public function countCashInCommission(int $amount, string $currency): string
    {
        $math = new Math(4);
        $commission = new Commission();
        $currency_rate = new Rate($currency);
        $rate = $currency_rate->getRate();

        $amount_in_eur = $math->divide((string) $amount, (string) $rate);

        $cash_in_commission_eur = $math->multiply($amount_in_eur, (string) $commission->cash_in_fee);

            if ($cash_in_commission_eur > $commission->cash_in_fee_max) {
                $cash_in_commission_eur = $commission->cash_in_fee_max;
            }
        
        $cash_in_commission = $math->multiply($cash_in_commission_eur, (string) $rate);

        return $cash_in_commission;
    }

    public function countCashOutCommission(int $amount, string $currency, string $date): string
    {
        
        if ($this->operationWeeksDiffer($date)) {
            $this->setOperationWeek($this->getOperationWeekNumber($date));
            $this->setLastOperationWeek($date);
            $this->setCashOutCount(0);
            $this->setCashPerWeek('0.00');
        }

        if (!$this->isLegal()) {
            $cash_out_commission = $this->countNaturalCashoutCommission($amount, $currency);
        } else {
            $cash_out_commission = $this->countLegalCashoutCommission($amount, $currency);
        }

        return $cash_out_commission;
    }

    public function countNaturalCashoutCommission(int $amount, string $currency): string
    {
        $commission = new Commission();
        $math = new Math(4);
        $currency_rate = new Rate($currency);
        $rate = $currency_rate->getRate();

        $amount_in_eur = $math->divide((string) $amount, (string) $rate);

        $user_cash_per_week = (string) $this->getCashPerWeek();
        $user_cash_out_count = $this->getCashOutCount();

        if ($user_cash_per_week > $commission->cash_out_limit) {
            $cash_out_commission = $math->multiply($amount_in_eur, $commission->cash_out_fee);
        } elseif ($user_cash_out_count >= $commission->cash_out_count_limit) {
            $cash_out_commission = $math->multiply($amount_in_eur, $commission->cash_out_fee);
        } elseif ($user_cash_per_week + $amount_in_eur > $commission->cash_out_limit) {
            $commission_part = $math->subtract($math->add($amount_in_eur, $user_cash_per_week), $commission->cash_out_limit);
            $cash_out_commission = $math->multiply($commission_part, $commission->cash_out_fee);
        } else {
            $cash_out_commission = '0.00';
        }

        $this->addToCashOutCount();
        $this->addToCashPerWeek($amount_in_eur);

        $cash_out_commission = $math->multiply($cash_out_commission, (string) $rate);

        return $cash_out_commission;
    }

    public function countLegalCashoutCommission(int $amount, string $currency): string
    {
        $commission = new Commission();
        $math = new Math(4);
        $currency_rate = new Rate($currency);
        $rate = $currency_rate->getRate();

        $amount_in_eur = $math->divide((string) $amount, (string) $rate);

        $cash_out_commission = $math->multiply($amount_in_eur, $commission->cash_out_fee);

        if ($cash_out_commission < $commission->cash_out_fee_min) {
            $cash_out_commission = $commission->cash_out_fee_min;
        }

        $cash_out_commission = $math->multiply($cash_out_commission, (string) $rate);

        return $cash_out_commission;
    }
}
