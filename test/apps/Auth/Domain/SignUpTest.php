<?php

declare(strict_types=1);

namespace HyperfTest\Apps\Auth\Domain;

use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dto\SignUpDto;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Entity\User;
use Auth\Domain\SignUp;
use Hyperf\Testing\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
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
        /** @var UserDaoInterface|MockObject */
        $userDao = $this->createMock(UserDaoInterface::class);
        $userDao->method("create")->willReturn(new User($this->dto->name, $this->dto->email, 'identify'));

        /** @var CredentialDaoInterface|MockObject */
        $credentialDao = $this->createMock(CredentialDaoInterface::class);
        $credentialDao->method("create")->willReturn(new Credential('hash', 'identify', 'identify'));

        /** @var SendEmailInterface|MockObject */
        $sendEmail = $this->createMock(SendEmailInterface::class);

        $resource = new SignUp($userDao, $credentialDao, $sendEmail);
        $resource = $resource->make($this->dto);

        $this->assertNotNull($resource->id);
        $this->assertSame("John Doe", $resource->name);
        $this->assertSame("test@example.com", (string) $resource->email);
    }

    public function testEmailExists()
    {
        /** @var UserDaoInterface|MockObject */
        $userDao = $this->createMock(UserDaoInterface::class);
        $userDao->method("emailAlreadyExists")->willReturn(true);

        /** @var CredentialDaoInterface|MockObject */
        $credentialDao = $this->createMock(CredentialDaoInterface::class);

        /** @var SendEmailInterface|MockObject */
        $sendEmail = $this->createMock(SendEmailInterface::class);

        $resource = new SignUp($userDao, $credentialDao, $sendEmail);

        $this->expectException(FieldException::class);
        $resource->make($this->dto);
    }
}
