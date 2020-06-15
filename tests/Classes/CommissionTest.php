<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Tests\Classes;

use PHPUnit\Framework\TestCase;
use Bank\CommissionTask\Classes\Commission;

class CommissionTest extends TestCase
{
    protected $commission;

    public function setUp()
    {
        $this->commission = new Commission();
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting
    *
    * @test */
    public function setting_cash_in_fee(string $number, string $expectation)
    {
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting_throws_invalid_argument
    *
    * @test */
    public function setting_cash_in_fee_throws_invalid_argument(string $number, string $expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting
    *
    * @test */
    public function setting_cash_in_fee_max(string $number, string $expectation)
    {
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting_throws_invalid_argument
    *
    * @test */
    public function setting_cash_in_fee_max_throws_invalid_argument(string $number, string $expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting
    *
    * @test */
    public function setting_cash_out_limit(string $number, string $expectation)
    {
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting_throws_invalid_argument
    *
    * @test */
    public function setting_cash_out_limit_throws_invalid_argument(string $number, string $expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }
    
    /**
    * @param int $int_number
    * @param int $int_expectation
    *
    * @dataProvider data_provider_for_setting_cash_out_count_limit
    *
    * @test */
    public function setting_cash_out_count_limit(int $int_number, int $int_expectation)
    {
        $this->commission->setCashOutCountLimit($int_number);
        $this->assertEquals($int_expectation, $this->commission->getCashOutCountLimit());
    }

    /**
    * @param int $int_number
    * @param int $int_expectation
    *
    * @dataProvider data_provider_for_setting_cash_out_count_limit_throws_invalid_argument_exception
    *
    * @test */
    public function setting_cash_out_count_limit_throws_invalid_argument(int $int_number, int $int_expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->commission->setCashOutCountLimit($int_number);
        $this->assertEquals($int_expectation, $this->commission->getCashOutCountLimit());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting
    *
    * @test */
    public function setting_cash_out_fee(string $number, string $expectation)
    {
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting_throws_invalid_argument
    *
    * @test */
    public function setting_cash_out_fee_throws_invalid_argument(string $number, string $expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting
    *
    * @test */
    public function setting_cash_out_fee_min(string $number, string $expectation)
    {
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }

    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_setting_throws_invalid_argument
    *
    * @test */
    public function setting_cash_out_fee_min_throws_invalid_argument(string $number, string $expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->commission->setCashInFee($number);
        $this->assertEquals($expectation, $this->commission->getCashInFee());
    }
    
    public function data_provider_for_setting(): array
    {
        return [
            'set with natural number' => ['1', '1'],
            'set with float number' => ['0.01', '0.01']
        ];
    }

    public function data_provider_for_setting_throws_invalid_argument(): array
    {
        return [
            'set with negative number' => ['-1', '-1'],
            'set with letter and number' => ['1k', '1k'],
            'set with string' => ['one', 'one']
        ];
    }

    public function data_provider_for_setting_cash_out_count_limit(): array
    {
        return [
            'set with natural number' => [1, 1],
            'set with float number' => [0.01, 0.01]
        ];
    }

    public function data_provider_for_setting_cash_out_count_limit_throws_invalid_argument_exception(): array
    {
        return [
            'set with negative number' => [-1, -1]
        ];
    }
}