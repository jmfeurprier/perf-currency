<?php

namespace perf\Currency;

use PHPUnit\Framework\TestCase;

class MoneyAmountTest extends TestCase
{
    public function testAdd()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $result = $moneyAmount->add(MoneyAmount::create(11111, 'CAD'));

        $this->assertSame(23456, $result->getAmount());
        $this->assertSame('CAD', $result->getCurrencyCode());
    }

    public function testDivide()
    {

    }

    public function testGetCurrencyCode()
    {

    }

    public function testEquals()
    {

    }

    public function testMultiply()
    {

    }

    public function testCreate()
    {

    }

    public function testGetAmount()
    {

    }

    public function testSubtract()
    {

    }

    public function testGetDecimalPart()
    {

    }

    public function testCreateFromFloat()
    {

    }

    public function testGetAmountAsString()
    {

    }

    public function testExchange()
    {

    }

    public function testGetIntegerPart()
    {

    }
}
