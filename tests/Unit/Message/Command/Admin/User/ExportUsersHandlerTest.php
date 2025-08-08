<?php

namespace App\Tests\Unit\Message\Command\Admin\User;

use App\Factory\UserFactory;
use App\Message\Command\Admin\User\ExportUsers;
use App\Message\Command\Admin\User\Handler\ExportUsersHandler;
use App\Repository\UserRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Zenstruck\Foundry\Test\Factories;

class ExportUsersHandlerTest extends TestCase
{
    use Factories;

    private EncoderInterface|MockObject $csvEncoder;

    private UserRepository|MockObject $userRepository;

    protected function setUp(): void
    {
        $this->csvEncoder = new CsvEncoder();
        $this->userRepository = $this->createMock(UserRepository::class);
    }

    #[Test]
    public function testDoExport(): void
    {
        $user = UserFactory::createOne();

        $this->userRepository
            ->expects($this->any())
            ->method('findAll')
            ->willReturn([
                $user,
            ])
        ;

        $handler = $this->generate();
        $csv = $handler(
            new ExportUsers(),
        );

        $this->assertIsString($csv);
        $this->assertStringContainsString('email', $csv);
    }

    private function generate(): ExportUsersHandler
    {
        return new ExportUsersHandler(
            csvEncoder: $this->csvEncoder,
            userRepository: $this->userRepository,
        );
    }
}
