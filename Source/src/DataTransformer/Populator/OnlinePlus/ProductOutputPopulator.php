<?php


namespace App\DataTransformer\Populator\OnlinePlus;


use App\DataProvider\Helper\AssetHelper;
use App\Dto\OnlinePlus\ProductCodeOutputDto;
use App\Dto\OnlinePlus\ProductLinksOutputDto;
use App\Dto\OnlinePlus\ProductOutputDto;
use App\Entity\OnlineProduct;
use App\Service\I18NService;
use Doctrine\DBAL\Query\QueryBuilder;
use Pimcore\Model\DataObject\Voucher as DataObjectVoucher;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductOutputPopulator implements ProductOutputPopulatorInterface
{

    private AssetHelper $assetHelper;
    private I18NService $i18NService;
    private RequestStack $requestStack;

    public function __construct(AssetHelper $assetHelper, I18NService $i18NService, RequestStack $requestStack)
    {
        $this->assetHelper = $assetHelper;
        $this->i18NService = $i18NService;
        $this->requestStack = $requestStack;
    }

    public function populate(OnlineProduct $source, ProductOutputDto $target): ProductOutputDto
    {
        $this->setGeneralFields($source, $target);
        $this->setLocalizedFields($source, $target);
        $this->setProductLinks($source, $target);
        $this->setProductCode($source, $target);

        return $target;
    }

    private function setGeneralFields(OnlineProduct $source, ProductOutputDto $target) {
        $target->productId = $source->getId();

        $target->validityInDays = 28;
        $target->originalPrice = $source->getOriginalPrice();
        $target->backgroundHex = $source->getColor();
        $target->iconUrl = $this->assetHelper->getAssetUrl($source->getLogo());
        $target->productCategory = $source->getCategory();
        $target->casProductId = $source->getCasProductId();
    }

    private function setLocalizedFields(OnlineProduct $source, ProductOutputDto $target) {
        $language = $this->i18NService->getLanguageFromRequest();

        $target->name = $source->getName($language);
        $target->description = $source->getDescription($language);
        $target->smallDescription = $source->getSmallDescription($language);
        $target->title = $source->getTitle($language);
        $target->subTitle = $source->getSubTitle($language);
        $target->contentTeaser = $source->getContentTeaser($language);
    }

    private function setProductLinks(OnlineProduct $source, ProductOutputDto $target) {
        $productLinksDto = new ProductLinksOutputDto();

        $productLinksDto->androidLink = $source->getAndroidLink();
        $productLinksDto->iOSLink = $source->getIOSLink();
        $productLinksDto->websiteLink = $source->getWebsiteLink();
        $productLinksDto->instructionsUrl = $source->getInstructionsUrl();

        $target->productLinksDto = $productLinksDto;
    }

    private function setProductCode(OnlineProduct $source, ProductOutputDto $target) {
        $productCodeDto = null;

        $casUserId = $this->requestStack->getCurrentRequest()->attributes->get('casUserId');

        $vouchers = new DataObjectVoucher\Listing;
        $vouchers->setCondition(
            'object_Voucher.o_path = ? AND  ExpirationDate > now() AND now() BETWEEN RedeemedDateTime AND DATE_ADD(RedeemedDateTime, INTERVAL ? MINUTE)',
           [$source->getRealFullPath() . '/Voucher/', $_ENV['VOUCHER_REDEEM_PERIOD_MINUTES'] ?? 40320, $casUserId]
        );
        $vouchers->onCreateQueryBuilder(
            function (QueryBuilder $queryBuilder) {
                $queryBuilder->join('object_Voucher', 'object_courseuser', 'user',
                    'object_Voucher.RedeemedUser__id = user.oo_id');
                $queryBuilder->andWhere('user.UserId = ?');
            }
        );

        if ($voucher = $vouchers->current()) {
            $productCodeDto = new ProductCodeOutputDto();
            $productCodeDto->code = $voucher->getVoucherCode();
            $productCodeDto->id = $voucher->getId();
        }

        $target->productCodeDto = $productCodeDto;
    }
}
