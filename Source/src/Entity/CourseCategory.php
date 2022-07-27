<?php


namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\CourseCategoryDto;
use Pimcore\Model\DataObject\CourseCategory as DataObjectCourseCategory;

/**
 * @ApiResource(
 *  attributes={},
 *  shortName="CourseCategory",
 *  normalizationContext={
 *      "allow_extra_attributes"=false
 *  },
 *  output=CourseCategoryDto::class,
 *  formats={"json"},
 *  collectionOperations={
 *     "get" = {
 *        "openapi_context"={
 *          "parameters"  = {
 *           {
 *                "name": "language",
 *                "type": "string",
 *                "in": "query",
 *                "required": false,
 *                "description": "Language parameter",
 *                "example": "de",
 *                "schema" = {
 *                     "type"="string"
 *                 }
 *            }
 *          },
 *          "security": {}
 *        }
 *     }
 *  },
 *  itemOperations={},
 * )
 */
final class CourseCategory extends DataObjectCourseCategory
{
    /**
     * @ApiProperty(identifier=true)
     */
    protected ?string $catId;


}
