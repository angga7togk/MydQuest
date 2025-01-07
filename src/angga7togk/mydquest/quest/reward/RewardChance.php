<?php

namespace angga7togk\mydquest\quest\reward;

final class RewardChance
{
  private int $value;

  public const COMMON = 1;
  public const UNCOMMON = 21;
  public const RARE = 51;
  public const EPIC = 76;
  public const LEGENDARY = 91;

  private const NAMES = [
    'COMMON' => [1, 20],
    'UNCOMMON' => [21, 50],
    'RARE' => [51, 75],
    'EPIC' => [76, 90],
    'LEGENDARY' => [91, 100],
  ];

  public function __construct(int $value)
  {
    if ($value < 1 || $value > 100) {
      throw new \InvalidArgumentException("Chance value must be between 1 and 100.");
    }

    $this->value = $value;
  }

  public function getName(): string
  {
    foreach (self::NAMES as $name => [$min, $max]) {
      if ($this->value >= $min && $this->value <= $max) {
        return $name;
      }
    }

    return 'UNCOMMON';
  }

  public function getValue(): int
  {
    return $this->value;
  }

  public static function fromName(string $name): self
  {
    if (!array_key_exists($name, self::NAMES)) {
      throw new \InvalidArgumentException("Invalid chance name: $name");
    }

    [$min, $max] = self::NAMES[$name];
    return new self($min);
  }
}
