<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class DateTimeVO
{
    private function __construct(
        public \DateTimeImmutable $value
    ) {
    }

    public static function now(): self
    {
        return new self(new \DateTimeImmutable());
    }

    public static function fromString(string $dateTime): self
    {
        return new self(new \DateTimeImmutable($dateTime));
    }

    public static function fromDateTime(\DateTimeInterface $dateTime): self
    {
        if ($dateTime instanceof \DateTimeImmutable) {
            return new self($dateTime);
        }

        return new self(\DateTimeImmutable::createFromMutable($dateTime));
    }

    public function format(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->value->format($format);
    }

    public function __toString(): string
    {
        return $this->format();  // Default format: Y-m-d H:i:s
    }

    public function toMysqlFormat(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function toIso8601(): string
    {
        return $this->value->format(\DateTimeInterface::ATOM);
    }

    public function toRfc3339(): string
    {
        return $this->value->format(\DateTimeInterface::RFC3339);
    }

    public function equals(DateTimeVO $other): bool
    {
        return $this->value == $other->value;
    }

    public function isBefore(DateTimeVO $other): bool
    {
        return $this->value < $other->value;
    }

    public function isAfter(DateTimeVO $other): bool
    {
        return $this->value > $other->value;
    }

    public function addDays(int $days): self
    {
        return new self($this->value->modify("+{$days} days"));
    }

    public function subDays(int $days): self
    {
        return new self($this->value->modify("-{$days} days"));
    }

    public function addHours(int $hours): self
    {
        return new self($this->value->modify("+{$hours} hours"));
    }

    public function diffInDays(DateTimeVO $other): int
    {
        return (int) $this->value->diff($other->value)->format('%a');
    }
}