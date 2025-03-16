<?php

declare(strict_types=1);

namespace HyperfTest\Apps\Auth\Domain\ValueObject;

use Auth\Domain\ValueObject\Password;
use Hyperf\Testing\TestCase;
use Shared\Exception\FieldException;

/**
 * @internal
 * @coversNothing
 */
class PasswordTest extends TestCase
{
    public function testMain(): void
    {
        $value = 'P@ssw0rd';
        $vl = new Password($value);

        $this->assertNotSame($vl, $vl->toString());
        $this->assertNotSame($vl, $vl->__toString());
        $this->assertNotSame($vl, (string) $vl);
    }

    public function testCheck(): void
    {
        $value = 'P@ssw0rd';
        $vl = new Password($value);

        $this->assertTrue($vl->check((string) $vl));
    }

    public function testPasswordInvalidMin8(): void
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches('/validation.password.min:8/');
        new Password('P@ssw0r');
    }

    public function testPasswordInvalidNotUpper(): void
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches('/validation.password.uppercase/');
        new Password('p@ssw0rd');
    }

    public function testPasswordInvalidNotLower(): void
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches('/validation.password.lowercase/');
        new Password('P@SSW0RD');
    }

    public function testPasswordInvalidNotNumber(): void
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches('/validation.password.number/');
        new Password('P@sswOrd');
    }

    public function testPasswordInvalidNotSpecial(): void
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches('/validation.password.special/');
        new Password('P4sswOrd');
    }
}
