<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\OnlinePlus\ProductOutputDto;
use Pimcore\Model\DataObject\OnlineProduct as DataObjectOnlineProduct;

/**
 * @ApiResource(
 *  attributes={
 *      "pagination_enabled"=false
 *     },
 *  shortName="Online+",
 *  normalizationContext={
 *      "allow_extra_attributes"=false,
 *      "skip_null_values" = false,
 *  },
 *  formats={"json"},
 *  collectionOperations={
 *     "get_products"={
 *          "method"="GET",
 *          "path"="/product/{casUserId}",
 *          "output"=ProductOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Retrieves the collection of available products for the given CAS user",
 *              "description"="Retrieves the collection of available products for the given CAS user",
 *              "parameters"={
 *                 {
 *                     "name": "language",
 *                     "type": "string",
 *                     "in": "query",
 *                     "required": false,
 *                     "description": "Language parameter",
 *                     "example": "de",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 },
 *                 {
 *                     "name": "casUserId",
 *                     "type": "string",
 *                     "in": "path",
 *                     "required": false,
 *                     "description": "CAS userId",
 *                     "example": "123456789",
 *                     "schema" = {
 *                          "type"="string"
 *                      }
 *                 }
 *             },
 *          },
 *      },
 *  },
 *  itemOperations={}
 * )
 */

final class OnlineProduct extends DataObjectOnlineProduct
{
    /**
     * @ApiProperty(identifier=true)
     */
    protected ?int $productId;

    public function getProductId(): ?int
    {
        return $this->productId;
    }
}
