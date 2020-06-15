<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Tests\Classes;

use PHPUnit\Framework\TestCase;
use Bank\CommissionTask\Classes\Controller;
use Bank\CommissionTask\Classes\User as User;

class ControllerTest extends TestCase
{
    
    protected $controller;
    protected $natural_user;
    protected $legal_user;

    public function setUp()
    {
        $this->controller = new Controller();
        $this->natural_user = new User(1, 'natural');
        $this->legal_user = new User(2, 'legal');
    }

    /** @test */
    public function method_converts_date_into_week_number()
    {
        $this->assertEquals('202023', $this->controller->getOperationWeekNumber('2020-06-01'));
    }

    /** @test */
    public function operation_week_is_setting()
    {
        $this->controller->setOperationWeek('202023');

        $this->assertEquals('202023', $this->controller->getOperationWeek());
    }

    /** @test */
    public function last_operation_week_is_setting()
    {
        $this->controller->setLastOperationWeek('202023');

        $this->assertEquals('202023', $this->controller->getLastOperationWeek());
    }

    /** @test */
    public function if_operationWeeksDiffer_returns_true_on_diff_week_numbers()
    {
        $this->controller->setOperationWeek('202022');
        $this->controller->getOperationWeek('2020-06-01');

        $this->assertTrue($this->controller->operationWeeksDiffer('2020-06-01'));
    }

    /** @test */
    public function if_operationWeeksDiffer_returns_false_on_same_week_numbers()
    {
        $this->controller->setOperationWeek('202023');
        $this->controller->getOperationWeek('2020-06-01');

        $this->assertFalse($this->controller->operationWeeksDiffer('2020-06-01'));
    }

    /** @test */
    public function date_checking()
    {
        $this->assertTrue($this->controller->dateCheck('2020-06-01'));
    }

    /** @test */
    public function operate_method_for_natural_user()
    {
        $this->assertEquals('0.60', $this->natural_user->operate('cash_out', '1200.00', 'EUR', '2014-12-31'));
    }

    /** @test */
    public function operate_method_for_legal_user()
    {
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '1000000.00', 'EUR', '2016-01-10'));
    }
    
    /** @test */
    public function invalid_operation_type_argument_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cashin', '1000000.00', 'EUR', '2016-01-10'));
    }

    /** @test */
    public function negative_amount_argument_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '-100', 'EUR', '2016-01-10'));
    }

    /** @test */
    public function invalid_amount_argument_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '100K', 'EUR', '2016-01-10'));
    }

    /** @test */
    public function invalid_currency_argument_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '1000000', 'eur', '2016-01-10'));
    }

    /** @test */
    public function invalid_date_argument_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '1000000', 'EUR', '2016-20-20'));
    }

    /** @test */
    public function date_with_slash_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '1000000', 'EUR', '2016/01/10'));
    }

    /** @test */
    public function date_with_commas_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '1000000', 'EUR', '2016.01.10'));
    }

    /** @test */
    public function wrong_order_date_for_operate_method()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertEquals('5.00', $this->legal_user->operate('cash_in', '1000000', 'EUR', '01-10-2016'));
    }

    /** @test */
    public function cash_adding()
    {
        $this->assertEquals('0.0300', $this->controller->addCash('100'));
    }

    /** @test */
    public function cash_adding_with_max_cashin_fee()
    {
        $this->assertEquals('5.00', $this->controller->addCash('100000000'));
    }

    /**
    * @param string $amount
    * @param string $date
    * @param string $expectation
    *
    * @dataProvider data_provider_for_take_cash_method_with_natural_user
    *
    * @test */
    public function take_cash_method_with_natural_user(string $amount, string $date, string $expectation)
    {
        $this->assertEquals($expectation, $this->natural_user->takeCash($amount, $date));
    }

    public function data_provider_for_take_cash_method_with_natural_user(): array
    {
        return [
            'params to check take cash method without commission' => ['100', '201401', '0.00'],
            'params to check take cash method with commission' => ['1100', '201401', '0.3000']
        ];
    }

    /**
    * @param string $amount
    * @param string $date
    * @param string $expectation
    *
    * @dataProvider data_provider_for_take_cash_method_with_legal_user
    *
    * @test */
    public function take_cash_method_with_legal_user(string $amount, string $date, string $expectation)
    {
        $this->assertEquals($expectation, $this->legal_user->takeCash($amount, $date));
    }

    public function data_provider_for_take_cash_method_with_legal_user(): array
    {
        return [
            'params to check take cash method with minimal cash out fee' => ['100', '201401', '0.5'],
            'params to check take cash method without basic cash out fee' => ['500', '201401', '1.5000']
        ];
    }

    /** @test */
    public function counting_natural_commission_without_commission()
    {
        $this->assertEquals('0.00', $this->natural_user->countNaturalCashoutCommission('100'));
    }

    /** @test */
    public function counting_natural_commission_with_commission()
    {
        $this->assertEquals('0.6000', $this->natural_user->countNaturalCashoutCommission('1200'));
    }

    /** @test */
    public function counting_natural_commission_with_commission_after_reaching_cashout_limit()
    {
        $this->natural_user->setCashOutCount(4);
        $this->assertEquals('0.3000', $this->natural_user->countNaturalCashoutCommission('100'));
    }

    /** @test */
    public function counting_legal_commission_when_actual_commission_is_less_then_min()
    {
        $this->assertEquals('0.5', $this->legal_user->countLegalCashoutCommission('100'));
    }

    /** @test */
    public function counting_legal_commission()
    {
        $this->assertEquals('3.0000', $this->legal_user->countLegalCashoutCommission('1000'));
    }
    
}
