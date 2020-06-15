<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Tests\Classes;

use PHPUnit\Framework\TestCase;
use Bank\CommissionTask\Classes\Converter;

class ConverterTest extends TestCase
{
    protected $converter;

    public function setUp()
    {
        $this->converter = new Converter();
    }

    /** @test */
    public function usd_to_eur_converting()
    {
        $this->assertEquals('86.9792', $this->converter->usdToEur('100'));
    }
    
    /** @test */
    public function jpy_to_eur_converting()
    {
        $this->assertEquals('0.7720', $this->converter->jpyToEur('100'));
    }

    /** @test */
    public function eur_to_usd_converting()
    {
        $this->assertEquals('114.9700', $this->converter->eurToUsd('100'));
    }
    
    /** @test */
    public function eur_to_jpy_converting()
    {
        $this->assertEquals('12953.0000', $this->converter->eurToJpy('100'));
    }
}