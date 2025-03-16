<?php

declare(strict_types=1);

namespace HyperfTest\Apps\Auth\Domain\ValueObject;

use Auth\Domain\ValueObject\Email;
use Hyperf\Testing\TestCase;
use Shared\Exception\FieldException;

/**
 * @internal
 * @coversNothing
 */
class EmailTest extends TestCase
{
    public function testMain(): void
    {
        $email = 'email@example.com';
        $vl = new Email($email);

        $this->assertSame($email, $vl->toString());
        $this->assertSame($email, $vl->__toString());
        $this->assertSame($email, (string) $vl);
    }

    public function testEmailInvalid(): void
    {
        $this->expectException(FieldException::class);
        new Email('emailDotexample.com');
    }
}
