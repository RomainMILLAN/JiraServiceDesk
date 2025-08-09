<?php

namespace App\Tests\Unit\Message\Event\Webhook\Issue;

use App\Factory\ProjectFactory;
use App\Factory\UserFactory;
use App\Message\Command\Common\EmailNotification;
use App\Message\Event\Webhook\Issue\Handler\IssueCreatedHandler;
use App\Message\Event\Webhook\Issue\IssueCreated;
use App\Repository\Jira\IssueRepository;
use App\Repository\ProjectRepository;
use JiraCloud\Issue\Issue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zenstruck\Foundry\Test\Factories;

class IssueCreatedHandlerTest extends TestCase
{
    use Factories;

    private readonly MessageBusInterface|MockObject $commandBus;

    private readonly ProjectRepository|MockObject $projectRepository;

    private readonly TranslatorInterface|MockObject $translator;

    private readonly IssueRepository|MockObject $issueRepository;

    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(MessageBusInterface::class);
        $this->projectRepository = $this->createMock(ProjectRepository::class);
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->issueRepository = $this->createMock(IssueRepository::class);
    }

    public static function emailNotificationDataProvider(): \Generator
    {
        yield 'can send notification' => [
            true,
        ];

        yield 'can\'t send notification' => [
            false,
        ];
    }

    #[Test]
    #[DataProvider('emailNotificationDataProvider')]
    public function testDoSendEmailNotification(bool $userHasPreferenceNotificationIssueCreated): void
    {
        $user = UserFactory::createOne([
            'email' => 'test@local.lan',
            'preferenceNotificationIssueCreated' => $userHasPreferenceNotificationIssueCreated,
        ]);

        $project = ProjectFactory::createOne([
            'jiraKey' => 'test',
        ]);
        $user->addProject($project);

        $this->projectRepository
            ->method('findOneBy')
            ->willReturn($project)
        ;

        $issue = $this->createMock(Issue::class);
        $this->issueRepository
            ->method('getFull')
            ->with('issueKey')
            ->willReturn($issue)
        ;

        $this->commandBus
            ->expects($userHasPreferenceNotificationIssueCreated ? self::once() : self::never())
            ->method('dispatch')
            ->with(
                self::isInstanceOf(EmailNotification::class),
            )
            ->willReturn(new Envelope($this->createMock(EmailNotification::class)))
        ;

        $handler = $this->generate();
        $handler(
            new IssueCreated(
                payload: [
                    'issue' => [
                        'key' => 'issueKey',
                        'fields' => [
                            'summary' => 'summary',
                            'project' => [
                                'id' => 'test',
                                'key' => 'test',
                            ],
                        ],
                    ],
                ],
            ),
        );
    }

    private function generate(): IssueCreatedHandler
    {
        $handler = new IssueCreatedHandler(
            commandBus: $this->commandBus,
            projectRepository: $this->projectRepository,
            translator: $this->translator,
            issueRepository: $this->issueRepository,
        );
        $logger = $this->createMock(LoggerInterface::class);
        $handler->setLogger($logger);

        return $handler;
    }
}
