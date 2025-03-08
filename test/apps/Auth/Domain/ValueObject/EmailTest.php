<?php

declare(strict_types=1);

namespace HyperfTest\Apps\Auth\Domain\ValueObject;

use Hyperf\Testing\TestCase;
use Auth\Domain\ValueObject\Email;
use Shared\Exception\FieldException;

class EmailTest extends TestCase
{
    public function testMain()
    {
        $email = "email@example.com";
        $vl = new Email($email);

        $this->assertSame($email, $vl->toString());
        $this->assertSame($email, $vl->__toString());
        $this->assertSame($email, (string) $vl);
    }

    public function testEmailInvalid()
    {
        $this->expectException(FieldException::class);
        new Email("emailDotexample.com");
    }
}
