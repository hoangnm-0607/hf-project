<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use App\DataTransformer\EndUser\EndUserUpdateImageInputDataTransformer;
use App\Dto\VPP\Assets\AssetDto;
use App\Entity\EndUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;

final class EndUserUpdateImageInputDataTransformerTest extends TestCase
{
    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;
    private EndUserUpdateImageInputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->transformer = new EndUserUpdateImageInputDataTransformer();
        $this->transformer->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->transformer,
        );
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
        yield [new AssetDto(), EndUser::class, ['item_operation_name' => 'update_image.as_admin'], true];
        yield [new AssetDto(), EndUser::class, ['item_operation_name' => 'get'], false];
        yield [new AssetDto(), \stdClass::class, ['item_operation_name' => 'update_image'], false];
        yield [null, EndUser::class, ['item_operation_name' => 'update_image.as_admin', 'input' => ['class' => AssetDto::class]], true];
    }

    public function testTransform(): void
    {
        $object = $this->createMock(AssetDto::class);
        $object->assetId = null;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($object)
        ;

        $endUser = $this->createMock(EndUser::class);
        $endUser
            ->expects(self::once())
            ->method('setUserImage')
        ;
        $endUser
            ->expects(self::once())
            ->method('save')
        ;

        $this->transformer->transform($object, EndUser::class, [AbstractNormalizer::OBJECT_TO_POPULATE => $endUser]);
    }
}
