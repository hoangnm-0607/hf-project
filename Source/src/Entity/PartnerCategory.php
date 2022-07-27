<?php


namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\PartnerCategoryDto;
use Pimcore\Model\DataObject\PartnerCategory as DataObjectPartnerCategory;

/**
 * @ApiResource(
 *  attributes={},
 *  shortName="PartnerCategory",
 *  normalizationContext={
 *      "allow_extra_attributes"=false
 *  },
 *  output=PartnerCategoryDto::class,
 *  formats={"json"},
 *  collectionOperations={
 *     "get" = {
 *        "openapi_context"={
 *          "parameters"  = {
 *              {
 *                  "name": "language",
 *                  "type": "string",
 *                  "in": "query",
 *                  "required": false,
 *                  "description": "Language parameter",
 *                  "example": "de",
 *                  "schema" = {
 *                     "type"="string"
 *                  }
 *              }
 *          },
 *          "security": {}
 *        }
 *     }
 *  },
 *  itemOperations={}
 * )
 */
final class PartnerCategory extends DataObjectPartnerCategory
{
    /**
     * @ApiProperty(identifier=true)
     */
    protected ?string $catId;


}
