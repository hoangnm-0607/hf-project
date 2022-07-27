<?php

namespace App\DataTransformer\OnlinePlus;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\OnlinePlus\VoucherRedeemInputDto;
use App\Entity\OnlineProduct;
use App\Entity\Voucher;
use App\Exception\AlreadyActiveVoucherException;
use App\Exception\NoVoucherLeftException;
use App\Exception\ObjectNotFoundException;
use App\Repository\VoucherRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\CourseUserService;
use App\Service\OnlinePlus\ProductService;
use Carbon\Carbon;
use Exception;
use Pimcore\Model\DataObject\Voucher as DataObjectVoucher;

class VoucherRedeemInputDataTransformer implements DataTransformerInterface
{
    private InMemoryUserValidator $inMemoryUserValidator;
    private ValidatorInterface $validator;
    private CourseUserService $courseUserService;
    private ProductService $productService;
    private VoucherRepository $voucherRepository;

    public function __construct(
        InMemoryUserValidator $inMemoryUserValidator,
        ValidatorInterface $validator,
        CourseUserService $courseUserService,
        VoucherRepository $voucherRepository,
        ProductService $productService
    ) {
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->validator             = $validator;
        $this->courseUserService     = $courseUserService;
        $this->productService        = $productService;
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * @param VoucherRedeemInputDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : DataObjectVoucher
    {
        $this->validator->validate($object);

        $this->inMemoryUserValidator->validateTokenAndApiUserId($object->casUserId);

        $product = OnlineProduct::getById($object->productId);
        if (!$product) {
            throw new ObjectNotFoundException('Product with ID ' . $product . ' not found');
        }

        $activeVoucher = $this->voucherRepository->getActiveVoucherForCasUserId($product, $object->casUserId);

        if ($activeVoucher->count() > 0) {
            throw new AlreadyActiveVoucherException('Active voucher found for ' . $object->casUserId);
        }

        $redeemableVoucher = $this->voucherRepository->getRedeemableVoucher($product);

        if ($redeemableVoucher->count() == 0) {
            throw new NoVoucherLeftException('No vouchers left');
        }

        $voucher = $redeemableVoucher->current();

        $courseUser = $this->courseUserService->getAndUpdateOrCreateCourseUser(
            [
                'userId' => $object->casUserId,
            ]
        );

        $voucher->setRedeemedDateTime(Carbon::now());
        $voucher->setRedeemedUser($courseUser);
        $voucher->save();

        // if we just redeemed the last voucher, deactivate product and notify partner
        if($redeemableVoucher->count() <= 1) {
            $this->productService->deactivateAndNotifyProduct($product);
        }

        return $voucher;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Voucher) {
            return false;
        }

        return $to === Voucher::class && (
                ($data instanceof VoucherRedeemInputDto) ||
                ($context['input']['class'] ?? null) === VoucherRedeemInputDto::class
            );
    }
}
