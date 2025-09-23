<?php
namespace Modules\UUID;

/** UUID v1: time-based */
class UuidV1 extends AbstractUuid
{
    protected static function versionDigit(): string { return '1'; }

    public static function generate(): self
    {
        $uuidEpoch = 0x01B21DD213814000; // 122192928000000000
        $now100ns = (int) (microtime(true) * 10000000);
        $ts = $now100ns + $uuidEpoch;

        $timeLow  = $ts & 0xffffffff;
        $timeMid  = ($ts >> 32) & 0xffff;
        $timeHi   = ($ts >> 48) & 0x0fff;
        $timeHi  |= 0x1000; // version 1

        // 14-bit clock sequence
        $clockSeq = random_int(0, 0x3fff) | 0x8000; // variant 10xx

        // 48-bit node (random, set multicast bit)
        $node = random_bytes(6);
        $node[0] = chr(ord($node[0]) | 0x01);

        $uuid = sprintf(
            '%08x-%04x-%04x-%04x-%012s',
            $timeLow,
            $timeMid,
            $timeHi,
            $clockSeq,
            bin2hex($node)
        );

        return new self($uuid);
    }
}