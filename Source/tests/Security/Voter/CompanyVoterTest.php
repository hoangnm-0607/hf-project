<?php

declare(strict_types=1);

namespace Tests\Security\Voter;

use App\Security\Voter\CompanyVoter;
use App\Service\InMemoryUserReaderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class CompanyVoterTest extends TestCase
{
    /** @var InMemoryUserReaderService|MockObject */
    protected InMemoryUserReaderService|MockObject $inMemoryUserReaderService;
    /** @var TokenInterface|MockObject */
    private TokenInterface|MockObject $token;

    private CompanyVoter $voter;

    protected function setUp(): void
    {
        $this->token = $this->createMock(TokenInterface::class);
        $this->inMemoryUserReaderService = $this->createMock(InMemoryUserReaderService::class);
        $this->voter = new CompanyVoter($this->inMemoryUserReaderService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->voter,
            $this->inMemoryUserReaderService,
            $this->token,
        );
    }

    public function testWithoutAttributes(): void
    {
        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $this->voter->vote($this->token, '12345', []));
    }

    public function testSupportAttributes(): void
    {
        self::assertFalse($this->voter->supportsAttribute('admin'));
        self::assertTrue($this->voter->supportsAttribute('company-admin'));
        self::assertTrue($this->voter->supportsAttribute('company-any-role'));
    }


    /**
     * @param string|null $subject
     * @param array       $attribute
     *
     * @dataProvider dataProviderForTestSupports
     */
    public function testSupports(?string $subject, array $attribute): void
    {
        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $this->voter->vote($this->token, $subject, $attribute));
    }

    public function dataProviderForTestSupports(): iterable
    {
        yield [
            null,
            'attribute' => [CompanyVoter::ADMIN],
        ];
        yield [
            null,
            'attribute' => [CompanyVoter::ANY_ROLE],
        ];
        yield [
            '12345',
            'attribute' => ['fake_attribute'],
        ];
    }

    /**
     * @dataProvider dataProviderForTestVoters
     */
    public function testVoteOnAttribute(string $lookingId, $attribute, $decodedToken, $result): void
    {
        $this->inMemoryUserReaderService
            ->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn($decodedToken)
        ;

        self::assertSame($result, $this->voter->vote($this->token, $lookingId, $attribute));
    }

    public function dataProviderForTestVoters(): iterable
    {
        yield [
            'lookingId' => '12345',
            'attribute' => [CompanyVoter::ADMIN],
            'decodedToken' => [
                [
                    'companyId' => '12345',
                    'role' => 'Admin',
                ],
            ],
            'result' => VoterInterface::ACCESS_GRANTED,
        ];
        yield [
            'lookingId' => '12345',
            'attribute' => [CompanyVoter::ADMIN],
            'decodedToken' => [
                [
                    'companyId' => '5321',
                    'role' => 'Admin',
                ],
            ],
            'result' => VoterInterface::ACCESS_DENIED,
        ];
        yield [
            'lookingId' => '12345',
            'attribute' => [CompanyVoter::ANY_ROLE],
            'decodedToken' => [
                [
                    'companyId' => '12345',
                ],
            ],
            'result' => VoterInterface::ACCESS_GRANTED,
        ];
        yield [
            'lookingId' => '12345',
            'attribute' => [CompanyVoter::ADMIN],
            'decodedToken' => [
                [
                    'companyId' => '1111',
                ],
                [
                    'companyId' => '12345',
                    'role' => 'Admin',
                ],
            ],
            'result' => VoterInterface::ACCESS_GRANTED,
        ];
        yield [
            'lookingId' => '12345',
            'attribute' => [CompanyVoter::ADMIN],
            'decodedToken' => [
                [
                    'companyId' => '12345',
                    'role' => 'User',
                ]
            ],
            'result' => VoterInterface::ACCESS_DENIED,
        ];
        yield [
            'lookingId' => '12345',
            'attribute' => [CompanyVoter::ANY_ROLE],
            'decodedToken' => [],
            'result' => VoterInterface::ACCESS_DENIED,
        ];
    }
}
