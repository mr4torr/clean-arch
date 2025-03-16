<?php

declare(strict_types=1);

namespace HyperfTest\Apps\Auth\Domain;

use Auth\Application\UseCase\SignUp;
use Auth\Domain\Dao\CredentialDaoInterface;
use Auth\Domain\Dao\UserDaoInterface;
use Auth\Domain\Dto\Input\SignUpDto;
use Auth\Domain\Email\SendEmailInterface;
use Auth\Domain\Entity\Credential;
use Auth\Domain\Entity\User;
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
        $this->dto = SignUpDto::make('John Doe', 'test@example.com', 'P@ssw0rd', 'api');
    }

    public function testMain(): void
    {
        /** @var MockObject|UserDaoInterface */
        $userDao = $this->createMock(UserDaoInterface::class);
        $userDao->method('create')->willReturn(new User($this->dto->name, $this->dto->email, 'identify'));

        /** @var CredentialDaoInterface|MockObject */
        $credentialDao = $this->createMock(CredentialDaoInterface::class);
        $credentialDao->method('create')->willReturn(new Credential('hash', 'identify', 'identify'));

        /** @var MockObject|SendEmailInterface */
        $sendEmail = $this->createMock(SendEmailInterface::class);

        $resource = new SignUp($userDao, $credentialDao, $sendEmail);
        $resource = $resource->make($this->dto);

        $this->assertNotNull($resource->id);
        $this->assertSame('John Doe', $resource->name);
        $this->assertSame('test@example.com', (string) $resource->email);
    }

    public function testEmailExists(): void
    {
        /** @var MockObject|UserDaoInterface */
        $userDao = $this->createMock(UserDaoInterface::class);
        $userDao->method('emailAlreadyExists')->willReturn(true);

        /** @var CredentialDaoInterface|MockObject */
        $credentialDao = $this->createMock(CredentialDaoInterface::class);

        /** @var MockObject|SendEmailInterface */
        $sendEmail = $this->createMock(SendEmailInterface::class);

        $resource = new SignUp($userDao, $credentialDao, $sendEmail);

        $this->expectException(FieldException::class);
        $resource->make($this->dto);
    }
}
