<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataTransformer\EndUser\EndUserUpdateDataTransformer;
use App\Dto\EndUser\EndUserInputDto;
use App\Entity\Company;
use App\Entity\EndUser;
use App\Security\AuthorizationAssertHelper;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class EndUserUpdateDataTransformerTest extends TestCase
{
    private EndUserUpdateDataTransformer $transformer;

    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);

        $this->transformer = new EndUserUpdateDataTransformer();
        $this->transformer->setAuthorizationAssertHelper($this->authorizationAssertHelper);
        $this->transformer->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->transformer,
            $this->authorizationAssertHelper,
        );
    }

    public function testTransform()
    {
        $object = new EndUserInputDto();

        $object->companyId = 616;
        $object->firstName = 'first-name';
        $object->lastName = 'last-name';
        $object->privateEmail = 'user.union@gmail.com';
        $object->businessEmail = 'user.union@gmail.com';
        $object->gender = 'male';
        $object->dateOfBirth = new Carbon('2020-02-12');
        $object->phoneNumber = '+380123456';

        $endUser = $this->createMock(EndUser::class);

        $endUser
            ->expects(self::once())
            ->method('getId')
            ->willReturn(611)
        ;

        $endUser->expects(self::once())->method('setBusinessEmail')->with($object->businessEmail);
        $endUser->expects(self::once())->method('setPrivateEmail')->with($object->privateEmail);
        $endUser->expects(self::once())->method('setPhoneNumber')->with($object->phoneNumber);
        $endUser->expects(self::once())->method('setDateOfBirth')->with(self::isInstanceOf(Carbon::class));
        $endUser->expects(self::once())->method('setFirstname')->with($object->firstName);
        $endUser->expects(self::once())->method('setLastname')->with( $object->lastName);
        $endUser->expects(self::once())->method('setGender')->with($object->gender);
        $endUser->expects(self::once())->method('getId')->willReturn(123);

        $company = $this->createMock(Company::class);
        $company
            ->expects(self::once())
            ->method('getId')
            ->willReturn(616)
        ;

        $endUser
            ->expects(self::once())
            ->method('getCompany')
            ->willReturn($company)
        ;

        $context = [AbstractNormalizer::OBJECT_TO_POPULATE => $endUser];

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyAdmin')
            ->with($object->companyId)
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($object)
        ;

        $endUser->expects(self::once())->method('save');

        $this->transformer->transform($object, EndUser::class, $context);
    }

    /**
     * @param mixed  $dto
     * @param string $to
     * @param array  $context
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportTransformation
     */
    public function testSupportsTransformation($dto, string $to, array $context, bool $supportResult): void
    {
        $result = $this->transformer->supportsTransformation($dto, $to, $context);
        self::assertSame($supportResult, $result);
    }

    public static function dataProviderSupportTransformation(): iterable
    {
        yield [new EndUserInputDto(), EndUser::class, ['item_operation_name' => 'update.as_admin'], true];
        yield [new EndUserInputDto(), EndUser::class, ['item_operation_name' => 'get'], false];
        yield [new EndUserInputDto(), \stdClass::class, ['item_operation_name' => 'update'], false];
        yield [null, EndUser::class, ['item_operation_name' => 'update.as_admin', 'input' => ['class' => EndUserInputDto::class]], true];
    }
}
