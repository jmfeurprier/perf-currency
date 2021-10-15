<?php

namespace perf\Currency;

use perf\Currency\Exception\CurrencyMismatchException;
use perf\Currency\Exception\DivisionByZeroException;
use perf\Currency\Exception\InvalidCurrencyException;
use perf\Currency\Exception\InvalidRoundingMethodException;

/**
 * Value object holding money amount and currency code.
 */
class MoneyAmount
{
    public const ROUND_DOWN      = 'DOWN';
    public const ROUND_HALF_DOWN = 'HALF_DOWN';
    public const ROUND_HALF_UP   = 'HALF_UP';
    public const ROUND_UP        = 'UP';

    private const ROUND_DEFAULT = self::ROUND_HALF_UP;

    private int $amount;

    private string $currencyCode;

    /**
     * @throws InvalidCurrencyException
     * @throws InvalidRoundingMethodException
     */
    public static function createFromFloat(
        float $amountFloat,
        string $currencyCode,
        string $roundingMethod = self::ROUND_DEFAULT
    ): self {
        $amount = self::round($amountFloat * 100, $roundingMethod);

        return new self($amount, $currencyCode);
    }

    /**
     * @throws InvalidCurrencyException
     */
    public static function create(
        int $amount,
        string $currencyCode
    ): self {
        return new self($amount, $currencyCode);
    }

    /**
     * @throws InvalidCurrencyException
     */
    private function __construct(
        int $amount,
        string $currencyCode
    ) {
        if (1 !== preg_match('/^[A-Z]{3}$/', $currencyCode)) {
            throw new InvalidCurrencyException('Invalid currency code format (expected 3 uppercased ASCII letters).');
        }

        $this->amount       = $amount;
        $this->currencyCode = $currencyCode;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getAmountAsString(): string
    {
        return $this->getIntegerPart() . '.' . $this->getDecimalPart();
    }

    public function getIntegerPart(): int
    {
        return (int) ($this->amount / 100);
    }

    public function getDecimalPart(): int
    {
        return (int) sprintf('%02d', (string) ($this->amount % 100));
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function equals(self $other): bool
    {
        if ($this->currencyCode === $other->currencyCode) {
            if ($this->amount === $other->amount) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws CurrencyMismatchException
     * @throws InvalidCurrencyException
     */
    public function add(self $other): self
    {
        $this->assertSameCurrency($other);

        $amount = ($this->amount + $other->amount);

        return self::create($amount, $this->currencyCode);
    }

    /**
     * @throws CurrencyMismatchException
     * @throws InvalidCurrencyException
     */
    public function subtract(self $other): self
    {
        $this->assertSameCurrency($other);

        $amount = ($this->amount - $other->amount);

        return self::create($amount, $this->currencyCode);
    }

    /**
     * @throws InvalidCurrencyException
     * @throws InvalidRoundingMethodException
     */
    public function multiply(
        float $multiplier,
        string $roundingMethod = self::ROUND_DEFAULT
    ): self {
        $amount = self::round($this->amount * $multiplier, $roundingMethod);

        return self::create($amount, $this->currencyCode);
    }

    /**
     * @throws DivisionByZeroException
     * @throws InvalidCurrencyException
     * @throws InvalidRoundingMethodException
     */
    public function divide(
        float $divider,
        string $roundingMethod = self::ROUND_DEFAULT
    ): self {
        if (0.0 === $divider) {
            $message = 'Cannot divide money amount: divider equals zero.';

            throw new DivisionByZeroException($message);
        }

        $amount = self::round($this->amount / $divider, $roundingMethod);

        return self::create($amount, $this->currencyCode);
    }

    /**
     * @throws InvalidCurrencyException
     * @throws InvalidRoundingMethodException
     */
    public function exchange(
        float $rate,
        string $targetCurrency,
        string $roundingMethod = self::ROUND_DEFAULT
    ): self {
        $amount = self::round($this->amount * $rate, $roundingMethod);

        return self::create($amount, $targetCurrency);
    }

    /**
     * @throws InvalidRoundingMethodException
     */
    private static function round(
        float $amount,
        string $roundingMethod
    ): int {
        switch ($roundingMethod) {
            case self::ROUND_DOWN:
                return (int) floor($amount);

            case self::ROUND_HALF_DOWN:
                return (int) round($amount, 0, PHP_ROUND_HALF_DOWN);

            case self::ROUND_HALF_UP:
                return (int) round($amount, 0, PHP_ROUND_HALF_UP);

            case self::ROUND_UP:
                return (int) ceil($amount);
        }

        throw new InvalidRoundingMethodException('Invalid rounding method.');
    }

    /**
     * @throws CurrencyMismatchException
     */
    private function assertSameCurrency(self $other): void
    {
        if ($this->currencyCode !== $other->currencyCode) {
            throw new CurrencyMismatchException("Currency codes don't match.");
        }
    }

    public function __toString(): string
    {
        return "{$this->getAmountAsString()} {$this->currencyCode}";
    }
}
