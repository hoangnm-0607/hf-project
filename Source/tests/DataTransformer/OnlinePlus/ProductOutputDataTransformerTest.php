<?php

namespace Tests\DataTransformer\OnlinePlus;

use App\DataTransformer\OnlinePlus\ProductOutputDataTransformer;
use App\DataTransformer\Populator\OnlinePlus\ProductOutputPopulatorInterface;
use App\Dto\OnlinePlus\ProductOutputDto;
use App\Entity\OnlineProduct;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class ProductOutputDataTransformerTest extends TestCase
{
    private MockObject|ProductOutputPopulatorInterface $populator;
    private ProductOutputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(ProductOutputPopulatorInterface::class);
        $this->dataTransformer = new ProductOutputDataTransformer([$this->populator]);
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new OnlineProduct(), ProductOutputDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new OnlineProduct(), stdClass::class);
        self::assertFalse($isSupports);
    }

    /**
     * @throws Exception
     */
    public function testTransform()
    {
        $data = new ProductOutputDto();
        $courseMock = $this->createMock(OnlineProduct::class);

        $this->populator->method('populate')->with($courseMock)->willReturn($data);

        $result = $this->dataTransformer->transform($courseMock, ProductOutputDto::class);

        self::assertEquals($data, $result);
    }
}
