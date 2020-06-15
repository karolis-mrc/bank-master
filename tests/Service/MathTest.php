<?php

declare(strict_types=1);

namespace Bank\CommissionTask\Tests\Service;

use PHPUnit\Framework\TestCase;
use Bank\CommissionTask\Service\Math;

class MathTest extends TestCase
{
    /**
     * @var Math
     */
    private $math;

    public function setUp()
    {
        $this->math = new Math(2);
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider data_provider_for_add_testing
     */
    public function test_add(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->add($leftOperand, $rightOperand)
        );
    }

    public function data_provider_for_add_testing(): array
    {
        return [
            'add 2 natural numbers' => ['1', '2', '3.00'],
            'add negative number to a positive' => ['-1', '2', '1.00'],
            'add natural number to a float' => ['1', '1.05123', '2.05'],
        ];
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider data_provider_for_subtract_testing
     */
    public function test_subtract(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->subtract($leftOperand, $rightOperand)
        );
    }

    public function data_provider_for_subtract_testing(): array
    {
        return [
            'subtract 2 natural numbers' => ['3', '2', '1.00'],
            'subtract positive number from a negative number' => ['-1', '2', '-3.00'],
            'subtract float number from a natural number' => ['2.05', '1', '1.05'],
        ];
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider data_provider_for_multiply_testing
     */
    public function test_multiply(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->multiply($leftOperand, $rightOperand)
        );
    }

    public function data_provider_for_multiply_testing(): array
    {
        return [
            'multiply 2 natural numbers' => ['3', '2', '6.00'],
            'multiply negative number with a positive number' => ['-1', '2', '-2.00'],
            'multiply float number with a natural number' => ['2.05', '2', '4.10'],
        ];
    }

    /**
     * @param string $leftOperand
     * @param string $rightOperand
     * @param string $expectation
     *
     * @dataProvider data_provider_for_divide_testing
     */
    public function test_divide(string $leftOperand, string $rightOperand, string $expectation)
    {
        $this->assertEquals(
            $expectation,
            $this->math->divide($leftOperand, $rightOperand)
        );
    }

    public function data_provider_for_divide_testing(): array
    {
        return [
            'divide 2 natural numbers' => ['3', '2', '1.50'],
            'divide negative number from a positive' => ['-1', '2', '-0.50'],
            'divide float number from a natural number' => ['2.05', '2', '1.02'],
        ];
    }

}
