<?php


namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\Company\CompanyFileCategoryDto;
use Pimcore\Model\DataObject\CompanyFileCategory as DataObjectCompanyFileCategory;


/**
 * @ApiResource(
 *  attributes={},
 *  shortName="CompanyFileCategory",
 *  normalizationContext={
 *      "allow_extra_attributes"=false
 *  },
 *  output=CompanyFileCategoryDto::class,
 *  formats={"json"},
 *  collectionOperations={
 *     "get-company-file-categories" = {
 *        "method"="GET",
 *        "openapi_context"={
 *          "summary" = "Get company file category  list.",
 *          "description" = "Get company file category  list.",
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
final class CompanyFileCategory extends DataObjectCompanyFileCategory
{

    /**
     * @ApiProperty(identifier=true)
     */
    protected ?string $categoryId;
}
