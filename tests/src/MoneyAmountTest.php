<?php

namespace perf\Currency;

use PHPUnit\Framework\TestCase;

class MoneyAmountTest extends TestCase
{
    public function testCreateFromFloat()
    {
        $moneyAmount = MoneyAmount::createFromFloat(123.45, 'CAD');

        $this->assertSame(12345, $moneyAmount->getAmount());
        $this->assertSame('CAD', $moneyAmount->getCurrencyCode());
    }

    public function testGetAmount()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertSame(12345, $moneyAmount->getAmount());
    }

    public function testGetCurrencyCode()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertSame('CAD', $moneyAmount->getCurrencyCode());
    }

    public function testGetIntegerPart()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertSame(123, $moneyAmount->getIntegerPart());
    }

    public function testGetDecimalPart()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertSame(45, $moneyAmount->getDecimalPart());
    }

    public function testEqualsWithDifferentCurrencies()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertFalse($moneyAmount->equals(MoneyAmount::create(12345, 'USD')));
    }

    public function testEqualsWithDifferentAmounts()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertFalse($moneyAmount->equals(MoneyAmount::create(23456, 'CAD')));
    }

    public function testEqualsWithSameAmountAndCurrency()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertTrue($moneyAmount->equals(MoneyAmount::create(12345, 'CAD')));
    }

    public function testAdd()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $result = $moneyAmount->add(MoneyAmount::create(11111, 'CAD'));

        $this->assertSame(23456, $result->getAmount());
        $this->assertSame('CAD', $result->getCurrencyCode());
    }

    public function testSubtract()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $result = $moneyAmount->subtract(MoneyAmount::create(11111, 'CAD'));

        $this->assertSame(1234, $result->getAmount());
        $this->assertSame('CAD', $result->getCurrencyCode());
    }

    public function testMultiply()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $result = $moneyAmount->multiply(1.2);

        $this->assertSame(14814, $result->getAmount());
        $this->assertSame('CAD', $result->getCurrencyCode());
    }

    public function testDivide()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $result = $moneyAmount->divide(2.5);

        $this->assertSame(4938, $result->getAmount());
        $this->assertSame('CAD', $result->getCurrencyCode());
    }

    public function testGetAmountAsString()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $this->assertSame('123.45', $moneyAmount->getAmountAsString());
    }

    public function testExchange()
    {
        $moneyAmount = MoneyAmount::create(12345, 'CAD');

        $result = $moneyAmount->exchange(1.23, 'USD');

        $this->assertSame(15184, $result->getAmount());
        $this->assertSame('USD', $result->getCurrencyCode());
    }
}
