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

    public function operate(string $operation_type, string $amount, string $currency, string $date): string
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

        if (!is_numeric($amount) || intval($amount) <= 0) {
            throw new \InvalidArgumentException('Amount of money should be numeric, more than zero and without any letters');
        }

        $currency_array = ['EUR', 'USD', 'JPY'];
        if (!in_array($currency, $currency_array, true)) {
            throw new \InvalidArgumentException("There is only three currency types available and it should be entered as 'EUR', 'USD' OR 'JPY'.");
        }

        if (!$this->dateCheck($date)) {
            throw new \InvalidArgumentException('Wrong date input or format');
        }

        $converter = new Converter();

        /*
         *  @ This switch depending on type of currency decides if it should use the converter and then
         *    depending on operation type, decides wich method to use 'addCash' or 'takeCash'.
         *    These methods return counted commission fee, then switch rounds it up and returns in format
         *    of string with scale 2 decimal.
         *  @ In JPY case fee is casted into string without formating after round up, as JPY smallest monetary unit
         *    is 1 JPY.
         */
        switch ($currency) {
            case 'EUR':
                $amount = $amount;
                ($operation_type === 'cash_in') ?
                    $commission_fee = $this->addCash($amount) :
                    $commission_fee = $this->takeCash($amount, $date);
                $commission_fee = ceil($commission_fee * 100) / 100;
                $commission_fee = number_format($commission_fee, 2, '.', '');
                break;
            case 'USD':
                $amount = $converter->usdToEur($amount);
                ($operation_type === 'cash_in') ?
                    $commission_fee = $this->addCash($amount) :
                    $commission_fee = $this->takeCash($amount, $date);
                $commission_fee = ceil($converter->eurToUsd($commission_fee) * 100) / 100;
                $commission_fee = number_format($commission_fee, 2, '.', '');
            break;
            case 'JPY':
                $amount = $converter->jpyToEur($amount);
                ($operation_type === 'cash_in') ?
                    $commission_fee = $this->addCash($amount) :
                    $commission_fee = $this->takeCash($amount, $date);
                $commission_fee = (string) ceil($converter->eurToJpy($commission_fee));
                break;
            default:
                throw new \InvalidArgumentException();
        }

        return $commission_fee;
    }

    public function addCash(string $amount): string
    {
        $commission = new Commission();
        $math = new Math(4);

        $cash_in_commission = $math->multiply($amount, $commission->cash_in_fee);

        if ($cash_in_commission > $commission->cash_in_fee_max) {
            $cash_in_commission = $commission->cash_in_fee_max;
        }

        return $cash_in_commission;
    }

    public function takeCash(string $amount, string $date): string
    {
        $converter = new Converter();
        $commission = new Commission();

        /*
         *  @ This if statement compares present operation and last operation dates and in case they differ, rests
         *    users operation counts and users cash per week accumulation.
         */
        if ($this->operationWeeksDiffer($date)) {
            $this->setOperationWeek($this->getOperationWeekNumber($date));
            $this->setLastOperationWeek($date);
            $this->setCashOutCount(0);
            $this->setCashPerWeek('0.00');
        }

        if (!$this->isLegal()) {
            $cash_out_commission = $this->countNaturalCashoutCommission($amount);
        } else {
            $cash_out_commission = $this->countLegalCashoutCommission($amount);
        }

        $this->addToCashOutCount();
        $this->addToCashPerWeek($amount);

        return $cash_out_commission;
    }

    public function countNaturalCashoutCommission(string $amount): string
    {
        $commission = new Commission();
        $math = new Math(4);

        $user_cash_per_week = $this->getCashPerWeek();
        $user_cash_out_count = $this->getCashOutCount();

        if ($user_cash_per_week > $commission->cash_out_limit) {
            $cash_out_commission = $math->multiply($amount, $commission->cash_out_fee);
        } elseif ($user_cash_out_count >= $commission->cash_out_count_limit) {
            $cash_out_commission = $math->multiply($amount, $commission->cash_out_fee);
        } elseif ($user_cash_per_week + $amount > $commission->cash_out_limit) {
            $commission_part = $math->subtract($math->add($amount, $user_cash_per_week), $commission->cash_out_limit);
            $cash_out_commission = $math->multiply($commission_part, $commission->cash_out_fee);
        } else {
            $cash_out_commission = '0.00';
        }

        return $cash_out_commission;
    }

    public function countLegalCashoutCommission(string $amount): string
    {
        $commission = new Commission();
        $math = new Math(4);

        $cash_out_commission = $math->multiply($amount, $commission->cash_out_fee);

        if ($cash_out_commission < $commission->cash_out_fee_min) {
            $cash_out_commission = $commission->cash_out_fee_min;
        }

        return $cash_out_commission;
    }
}
