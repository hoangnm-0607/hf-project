<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Populator\EndUser;

use App\DataProvider\Helper\AssetHelper;
use App\DataTransformer\Populator\EndUser\EndUserOutputPopulator;
use App\Dto\EndUser\EndUserOutputDto;
use App\Entity\EndUser;
use App\Service\EndUser\EndUserManager;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset\Image;

final class EndUserOutputPopulatorTest extends TestCase
{
    /** @var AssetHelper|MockObject */
    private AssetHelper|MockObject $assetHelper;

    /** @var EndUserManager|MockObject */
    private EndUserManager|MockObject $endUserService;

    private EndUserOutputPopulator $populator;

    protected function setUp(): void
    {
        $this->assetHelper = $this->createMock(AssetHelper::class);
        $this->endUserService = $this->createMock(EndUserManager::class);

        $this->populator = new EndUserOutputPopulator($this->assetHelper, $this->endUserService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->populator,
            $this->assetHelper,
            $this->endUserService,
        );
    }

    public function testPopulate(): void
    {
        $source = $this->createMock(EndUser::class);
        $target = new EndUserOutputDto();

        $date = $this->createMock(Carbon::class);
        $image = $this->createMock(Image::class);

        $source->expects(self::once())->method('getId')->willReturn(123);
        $source->expects(self::once())->method('getStatus')->willReturn('Active');
        $source->expects(self::once())->method('getPhoneNumber')->willReturn('+3806812312312');
        $source->expects(self::once())->method('getFirstname')->willReturn('first-name');
        $source->expects(self::once())->method('getLastname')->willReturn('last-name');
        $source->expects(self::once())->method('getBusinessEmail')->willReturn('email@gmail.com');
        $source->expects(self::once())->method('getPrivateEmail')->willReturn('email@gmail.com');
        $source->expects(self::once())->method('getDateOfBirth')->willReturn($date);
        $source->expects(self::once())->method('getRegistrationDate')->willReturn($date);
        $source->expects(self::once())->method('getGender')->willReturn('male');
        $source->expects(self::once())->method('getUserImage')->willReturn($image);

        $this->endUserService
            ->expects(self::once())
            ->method('prepareCustomFields')
            ->with($source)
            ->willReturn([])
        ;

        $this->assetHelper
            ->expects(self::once())
            ->method('getAssetUrl')
            ->with($image)
            ->willReturn('http://image.jpg')
        ;

        $date
            ->expects(self::once())
            ->method('format')
            ->with('Y-m-d')
            ->willReturn('2020-02-12')
        ;

        $this->populator->populate($source, $target);
        self::assertSame('first-name', $target->firstName);
        self::assertSame('email@gmail.com', $target->privateEmail);
    }
}
