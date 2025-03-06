<?php

declare(strict_types=1);

namespace HyperfTest\Apps\Auth\Domain;

use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dto\SignUpDto;
use Auth\Domain\SignUp;
use Hyperf\Testing\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Shared\Exception\FieldException;

/**
 * @internal
 * @coversNothing
 */
class SignUpTest extends TestCase
{
    private SignUpDto $dto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dto = SignUpDto::make("John Doe", "test@example.com", "P@ssw0rd", "api");
    }

    public function testMain()
    {
        $resource = new SignUp(
            $this->createMock(UserDaoInterface::class),
            $this->createMock(CredentialDaoInterface::class),
            $this->createMock(EventDispatcherInterface::class)
        );
        $resource = $resource->make($this->dto);

        $this->assertNotNull($resource->id);
        $this->assertSame("John Doe", $resource->name);
        $this->assertSame("test@example.com", $resource->email);
    }

    public function testEmailExists()
    {
        /** @var UserDaoInterface|MockObject */
        $user = $this->createMock(UserDaoInterface::class);
        $user->method("emailAlreadyExists")->willReturn(true);

        $resource = new SignUp(
            $user,
            $this->createMock(CredentialDaoInterface::class),
            $this->createMock(EventDispatcherInterface::class)
        );

        $this->expectException(FieldException::class);
        $resource->make($this->dto);
    }
}
