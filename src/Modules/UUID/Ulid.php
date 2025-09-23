<?php
namespace Modules\UUID;

use InvalidArgumentException;

/** ULID: Crockford Base32, 26 chars, lexicographically sortable */
class Ulid
{
    private const ALPHABET = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';

    private string $value;

    private function __construct(string $value)
    {
        self::assertValid($value);
        $this->value = strtoupper($value);
    }

    // ----- Instance API -----

    public function toString(): string { return $this->value; }
    public function __toString(): string { return $this->value; }

    public function equals(self|string $other): bool
    {
        $a = $this->value;
        $b = $other instanceof self ? $other->value : strtoupper($other);
        return $a === $b;
    }

    // ----- Static API -----

    public static function generate(): self
    {
        $ms = (int) floor(microtime(true) * 1000);
        $timePart = self::encodeTime($ms);
        $randomPart = self::encodeRandom(random_bytes(10));
        return new self($timePart . $randomPart);
    }

    public static function fromString(string $ulid): self
    {
        return new self($ulid);
    }

    public static function isValid(string $ulid): bool
    {
        return (bool) preg_match('/^[0-9A-HJKMNP-TV-Z]{26}$/i', $ulid);
    }

    public static function compare(self|string $a, self|string $b): int
    {
        $as = $a instanceof self ? $a->value : strtoupper($a);
        $bs = $b instanceof self ? $b->value : strtoupper($b);
        return $as <=> $bs;
    }

    private static function assertValid(string $ulid): void
    {
        if (!self::isValid($ulid)) {
            throw new InvalidArgumentException('Invalid ULID string');
        }
    }

    private static function encodeTime(int $ms): string
    {
        $alphabet = self::ALPHABET;
        $out = '';
        for ($i = 0; $i < 10; $i++) {
            $out = $alphabet[$ms & 31] . $out;
            $ms = intdiv($ms, 32);
        }
        return $out;
    }

    private static function encodeRandom(string $bytes10): string
    {
        if (strlen($bytes10) !== 10) {
            throw new InvalidArgumentException('ULID random input must be 10 bytes');
        }
        $alphabet = self::ALPHABET;
        $bits = 0;
        $value = 0;
        $out = '';

        for ($i = 0; $i < 10; $i++) {
            $value = ($value << 8) | ord($bytes10[$i]);
            $bits += 8;
            while ($bits >= 5) {
                $index = ($value >> ($bits - 5)) & 31;
                $bits -= 5;
                $out .= $alphabet[$index];
            }
        }
        if ($bits > 0) {
            $index = ($value << (5 - $bits)) & 31;
            $out .= $alphabet[$index];
        }
        return substr($out, 0, 16);
    }
}