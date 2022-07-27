<?php

namespace Tests\DataTransformer\OnlinePlus;

use App\DataTransformer\OnlinePlus\VoucherRedeemOutputDataTransformer;
use App\Dto\OnlinePlus\VoucherRedeemOutputDto;
use App\Entity\OnlineProduct;
use App\Service\DataObjectService;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Voucher;
use stdClass;

class VoucherRedeemOutputDataTransformerTest extends TestCase
{
    private DataObjectService $dataObjectService;
    private VoucherRedeemOutputDataTransformer $voucherRedeemOutputDataTransformer;

    protected function setUp(): void
    {
        $this->dataObjectService = $this->createMock(DataObjectService::class);
        $this->voucherRedeemOutputDataTransformer = new VoucherRedeemOutputDataTransformer($this->dataObjectService);
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->voucherRedeemOutputDataTransformer->supportsTransformation(new Voucher(), VoucherRedeemOutputDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->voucherRedeemOutputDataTransformer->supportsTransformation(new Voucher(), stdClass::class);
        self::assertFalse($isSupports);
    }

    public function testTransform()
    {
        $code = 42;
        $casPublicId = 'casPublicId';
        $templateKey = 'templateKey';

        $voucher = new Voucher();
        $voucher->setVoucherCode($code);

        $product = new OnlineProduct();
        $product->setCasPublicId($casPublicId);
        $product->setTemplateKey($templateKey);

        $this->dataObjectService->method('getRecursiveParent')->willReturn($product);

        $result = $this->voucherRedeemOutputDataTransformer->transform($voucher, VoucherRedeemOutputDto::class);

        self::assertEquals(VoucherRedeemOutputDto::class, get_class($result));
        self::assertEquals($code, $result->code);
        self::assertEquals($casPublicId, $result->casPublicId);
        self::assertEquals($templateKey, $result->templateKeyCas);
    }
}
