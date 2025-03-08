<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Apps\Auth\Domain\ValueObject;

use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dto\SignUpDto;
use Auth\Domain\SignUp;
use Auth\Domain\ValueObject\Password;
use Hyperf\Testing\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Shared\Exception\FieldException;

/**
 * @internal
 * @coversNothing
 */
class PasswordTest extends TestCase
{
    public function testMain()
    {
        $value = "P@ssw0rd";
        $vl = new Password($value);

        $this->assertNotSame($vl, $vl->toString());
        $this->assertNotSame($vl, $vl->__toString());
        $this->assertNotSame($vl, (string) $vl);
    }

    public function testCheck()
    {
        $value = "P@ssw0rd";
        $vl = new Password($value);

        $this->assertTrue($vl->check((string) $vl));
    }

    public function testPasswordInvalidMin8()
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches("/validation.password.min:8/");
        new Password("P@ssw0r");
    }

    public function testPasswordInvalidNotUpper()
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches("/validation.password.uppercase/");
        new Password("p@ssw0rd");
    }

    public function testPasswordInvalidNotLower()
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches("/validation.password.lowercase/");
        new Password("P@SSW0RD");
    }

    public function testPasswordInvalidNotNumber()
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches("/validation.password.number/");
        new Password("P@sswOrd");
    }

    public function testPasswordInvalidNotSpecial()
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessageMatches("/validation.password.special/");
        new Password("P4sswOrd");
    }
}
