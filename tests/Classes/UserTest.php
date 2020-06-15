<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Tests\Classes;

use PHPUnit\Framework\TestCase;
use Bank\CommissionTask\Classes\User;

class UserTest extends TestCase
{
    protected $natural_user;
    protected $legal_user;

    public function setup() {
        $this->natural_user = new User(1, 'natural');
        $this->legal_user = new User(2, 'legal');
    }

    /** @test */
    public function setting_id()
    {
        $this->natural_user->setId(3);
        $this->assertEquals(3, $this->natural_user->getId());
    }

    /** @test */
    public function setting_id_throws_invalid_argument_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->natural_user->setId(-1);
        $this->assertEquals(-1, $this->natural_user->getId());
    }

    /** @test */
    public function setting_user_type()
    {
        $this->natural_user->setUserType('legal');
        $this->assertEquals('legal', $this->natural_user->getUserType());
    }

    /** @test */
    public function setting_user_type_throws_invalid_argument_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->natural_user->setUserType('Legal');
        $this->assertEquals('Legal', $this->natural_user->getUserType());
    }

    /** @test */
    public function if_user_type_is_legal()
    {
        $this->assertTrue($this->legal_user->isLegal());
    }
    
    /** @test */
    public function if_user_type_is_natural()
    {
        $this->assertFalse($this->natural_user->isLegal());
    }
    
    /** @test */
    public function setting_cash_out_count()
    {
        $this->natural_user->setCashOutCount(4);
        $this->assertEquals(4, $this->natural_user->getCashOutCount());
    }
    
    /** @test */
    public function setting_cash_out_count_throws_invalid_argument_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->natural_user->setCashOutCount(-4);
        $this->assertEquals(-4, $this->natural_user->getCashOutCount());
    }
    
    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_set_cash_per_week
    *
    * @test */
    public function setting_cash_per_week_amount(string $number, string $expectation)
    {
        $this->natural_user->setCashPerWeek($number);
        $this->assertEquals($expectation, $this->natural_user->getCashPerWeek());
    }

    public function data_provider_for_set_cash_per_week()
    {
        return [
            'set with natural number' => ['100', '100'],
            'set with float' => ['100.1', '100.1']
        ];
    }
    
    /**
    * @param string $number
    * @param string $expectation
    *
    * @dataProvider data_provider_for_set_cash_per_week_throws_invalid_argument_exception
    *
    * @test */
    public function set_cash_per_week_throws_invalid_argument_exception(string $number, string $expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->natural_user->setCashPerWeek($number);
        $this->assertEquals($expectation, $this->natural_user->setCashPerWeek());
    }

    public function data_provider_for_set_cash_per_week_throws_invalid_argument_exception()
    {
        return [
            'set with negative number' => ['-100', '-100'],
            'set with letter and number' => ['1k', '-1k'],
            'set with string' => ['one', 'one']
        ];
    }

    /**
    * @param string $currency
    * @param string $expectation
    *
    * @dataProvider data_provider_for_currency_setting
    *
    * @test */
    public function setting_currency(string $currenct, string $expectation)
    {
        $this->natural_user->setCurrency($currenct);
        $this->assertEquals($expectation, $this->natural_user->getCurrency());
    }

    public function data_provider_for_currency_setting()
    {
        return [
            'set with eur' => ['EUR', 'EUR'],
            'set with usd' => ['USD', 'USD'],
            'set with jpy' => ['JPY', 'JPY']
        ];
    }

    /**
    * @param string $currency
    * @param string $expectation
    *
    * @dataProvider data_provider_for_currency_setting
    *
    * @test */
    public function setting_currency_throws_invalid_argument_exception(string $number, string $expectation)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->natural_user->setCashPerWeek($number);
        $this->assertEquals($expectation, $this->natural_user->setCashPerWeek());
    }

    public function data_provider_for_setting_currency_throws_invalid_argument_exception()
    {
        return [
            'set with lower case' => ['eur', 'eur'],
            'set with mixed caser' => ['Usd', 'Usd'],
            'set with different currency' => ['RUB', 'RUB']
        ];
    }

    /** @test */
    public function adding_to_count_of_operation()
    {
        $this->natural_user->addToCashOutCount();
        $this->assertEquals(1, $this->natural_user->getCashOutCount());
    }
    
    /** @test */
    public function adding_amount_of_money_to_cash_per_week()
    {
        $this->natural_user->addToCashPerWeek('200');
        $this->assertEquals('200.0000', $this->natural_user->getCashPerWeek());
    }

}